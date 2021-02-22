<?php


    namespace Modules\Content\Controllers;


    use Illuminate\Http\Request;
    use Illuminate\Support\Arr;
    use Illuminate\Support\Facades\Storage;
    use Modules\Content\Exports\ContentExport;
    use Modules\Content\Imports\ContentImport;
    use Modules\Content\Models\Content;
    use Modules\ModuleController;

    class ContentController extends ModuleController
    {

        /**
         * @var Content
         */
        private $content;


        public function __construct(Content $content)
        {
            parent::__construct();
            $this->content = $content;
        }

        public function search(Request $request)
        {
            $per_page = $request->queryParams['per_page'] ?? LIMIT;
            $query = $this->content->search($request);
            $rows = $query->with(['subContract', 'contract'])->orderBy('id', 'desc')->paginate($per_page);
            $rows->map(function ($item) {
                $item->items_provided = $item->contract->items_provided;
                $item->scope_of_supply = $item->contract->scope_of_supply;
                $item->auto_renewed_contract_html = $item->contract->auto_renewed_html;
                return $item;
            });
            $data['total_rows'] = $rows->total();
            $data['rows'] = $rows->items();
            $columns = [
                'id'                         => "id",
                'contract_name'              => 'Số Hợp đồng',
                'name'                       => "Tên",
                'singer_name'                => "Tên ca sĩ",
                'author_name'                => "Tên tác giả",
                'author_lyric_name'          => "Tên tác giả viết lời",
                'topic'                      => "Chủ đề",
                'channel_name'               => "Tên kênh",
                'video_url'                  => "Link video",
                'one_permission'             => '1 Quyền',
                'full_permission'            => 'Đủ quyền',
                'monopoly_permission'        => "Độc quyền",
                'sub_contract_number'        => 'Mã Phụ lục',
                'sub_contract_order'         => "Số TT phụ lục",
                'sign_date'                  => "Ngày ký",
                'exp_date'                   => "Ngày hết hạn",
                'auto_renewed_contract_html' => "Tự động gia hạn",
                'items_provided'             => "Hạng mục cung cấp",
                'scope_of_supply'            => "Phạm vi cung cấp",
                'status_html'                => "Trạng thái",
                'action'                     => "Hành động",
            ];
            $filtering = [
                'name',
                'contract_name',
                'sub_contract_name',
                'singer_name',
                'author_name',
                'channel_name',
                'items_provided',
                'one_permission',
                'full_permission',
                'monopoly_permission',
                'scope_of_supply',
            ];

            if ($request->content_type == BAI_HAT) {
                $columns = Arr::except($columns, ['author_lyric_name', 'channel_name', 'topic', 'video_url']);
            }
            if ($request->content_type == VIDEO) {
                $columns = Arr::except($columns, ['author_lyric_name', 'author_name', 'singer_name', 'items_provided','name']);
            }
            if ($request->content_type == TAC_GIA) {
                $columns = Arr::except($columns, ['topic', 'video_url', 'channel_name','name']);
            }
            foreach ($columns as $k => $column) {
                $dataColumn = [
                    "label" => $column,
                    "name"  => $k,
                ];

                if ($k == 'id') {
                    $dataColumn['sort'] = true;
                    $dataColumn['uniqueId'] = true;
                }

                if (in_array($k, $filtering)) {
                    $dataColumn['filter'] = ["type" => "simple", "case_sensitive" => true, 'showClearButton' => false, 'debounceRate' => 300];
                }
                if (in_array($k, ['sign_date', 'exp_date'])) {
                    $dataColumn["sort"] = true;
                }
                if (in_array($k, ['status_html'])) {
                    $dataColumn['slot_name'] = $k;
                    if ($k == 'status_html') {
                        $dataColumn['filter'] = [
                            'type'        => 'select',
                            'mode'        => 'single',
                            'placeholder' => 'Chọn Trạng thái',
                            'options'     => [
                                [
                                    'name'  => CONTRACT_STATUS[NHAP],
                                    'value' => NHAP,
                                ],
                                [
                                    'name'  => CONTRACT_STATUS[CON_HAN],
                                    'value' => CON_HAN,
                                ],
                                [
                                    'name'  => CONTRACT_STATUS[HET_HAN],
                                    'value' => HET_HAN,
                                ],
                                [
                                    'name'  => CONTRACT_STATUS[SAP_HET_HAN],
                                    'value' => SAP_HET_HAN,
                                ],
                                [
                                    'name'  => CONTRACT_STATUS[DA_THANH_LY],
                                    'value' => DA_THANH_LY,
                                ],
                            ],

                        ];
                    }
                }
                $data['columns'][] = $dataColumn;
            }
            return response()->json(['status' => 1, 'data' => $data]);
        }

        public function edit(Request $request, $id)
        {
            $contentType = $request->content_type;

            $contract = Content::getModelByContentType($contentType)->find($id);
            if (!empty($contract)) {
                $data['data'] = $contract;
                $data['status'] = 1;
                return response()->json($data);
            } else {
                $data['status'] = 0;
                $data['message'] = 'Có lỗi xẩy ra. Vui lòng thử lại';
                return response()->json($data);
            }
        }

        public function store(Request $request, $id = null)
        {
            $inputAll = $request->all();
            $contentType = $request->content_type;

            $content = Content::getModelByContentType($contentType);
            if ($id) {
                $content = $content->find($id);
            }
            try {
                $rules = [
                    'content_type'    => 'required',
                    'contract_number' => 'required',
                    'name'            => 'required',
                    //                    'status'          => 'required',
                    'create_user',
                    'update_user',
                ];
                $this->validate($request, $rules);
                $content->fill($inputAll);
                $sign_date = new \DateTime($request->sign_date);
                $exp_date = new \DateTime($request->exp_date);
                $content->sign_date = $sign_date->format('Y-m-d H:i:s');
                $content->exp_date = $exp_date->format('Y-m-d H:i:s');
                if ($content->save()) {
                    if (!empty($request->image)) {
                        $newArr = [];
                        foreach ($request->image as $image) {
                            $newArr[] = [
                                'object_id'    => $content->id,
                                'object_model' => $content->type,
                                'file_id'      => $image,
                            ];
                        }
                        $content->mediaFiles()->sync($newArr);
                    }
                }
                return response()->json(['status' => 1, 'message' => 'Thành công']);
            } catch (\Exception $exception) {
                return response()->json(['status' => 0, 'message' => $exception->getMessage()]);
            }


        }

        public function delete(Request $request)
        {
            $ids = $request->ids;
            $contentType = $request->content_type;
            if (!empty($contentType) and !empty($ids) and is_array($ids)) {
                $query = Content::getModelByContentType($contentType);
                $query->whereIn('id', $request->ids)->delete();
                return $this->sendSuccess([], 'Xóa thành công');
            }
            return $this->sendError('Không tìm thấy thông tin. Vui lòng thử lại');
        }

        public function export(Request $request)
        {
            try {
                $fileName = __('Quản lý nội dung :time.xlsx', ['time' => time()]);
                if (Storage::disk('export')->exists($fileName)) {
                    Storage::disk('export')->delete($fileName);
                }
                $export = (new ContentExport($request))->store($fileName, 'export');
                if ($export) {
                    $filePath = Storage::disk('export')->url($fileName);
                    return $this->sendSuccess(['url' => $filePath]);
                } else {
                    return $this->sendError('Có lỗi xảy ra. Vui lòng thử lại!');
                }
            } catch (\Exception $e) {
                return $this->sendError($e->getMessage());
            }
        }

        public function import(Request $request)
        {
            if (!empty($request->file)) {
                try {
                    (new ContentImport($request))->import($request->file);
                    return $this->sendSuccess([], 'Import thành công');
                } catch (\Exception $e) {
                    return $this->sendError($e->getMessage());
                }
            }

        }

    }

