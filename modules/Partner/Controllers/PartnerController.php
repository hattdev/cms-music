<?php


    namespace Modules\Partner\Controllers;


    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Http\Request;
    use Illuminate\Support\Arr;
    use Illuminate\Support\Facades\Storage;
    use Modules\ModuleController;
    use Modules\Partner\Exports\PartnerExport;
    use Modules\Partner\Imports\PartnerImport;
    use Modules\Partner\Models\Partner;

    class PartnerController extends ModuleController
    {

        /**
         * @var Partner
         */
        private $partner;


        public function __construct(Partner $partner)
        {
            parent::__construct();
            $this->partner = $partner;
        }

        public function search(Request $request)
        {
            $per_page = $request->queryParams['per_page'] ?? LIMIT;
            $query = $this->partner->search($request);
            $rows = $query->paginate($per_page);
            $rows->map(function ($item) {
                $item->sign_date = $item->contract->sign_date;
                $item->exp_date = $item->contract->exp_date;
                $item->tax_code = $item->contract->tax_code;
                $item->items_provided = $item->contract->items_provided;
                $item->scope_of_supply = $item->contract->scope_of_supply;
                $item->auto_renewed_contract_html = $item->contract->auto_renewed_html;
                return $item;
            });
            $data['total_rows'] = $rows->total();
            $data['rows'] = $rows->items();

            $columns = [
                'id'                => "id",
                'contract_name'   => 'Số Hợp đồng',
                'name'              => 'Tên Đối tác',
                'tax_code'              => 'Mã số thuế',
                'sign_date'         => "Ngày ký",
                'exp_date'          => "Ngày hết hạn",
                'auto_renewed_contract_html' => "Tự động gia hạn",
                'items_provided'    => "Hạng mục cung cấp",
                'scope_of_supply'   => "Phạm vi cung cấp",
                'status_html'       => "Trạng thái",
                'action'            => "Hành động",
            ];
            $filtering = [
                'contract_name',
                'name',
                'tax_code',
            ];
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
            $row = $this->partner->find($id);
            if (!empty($row)) {
                $data['data'] = $row;
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
            if (!$id) {
                $row = $this->partner;
            } else {
                $row = $this->partner->find($id);
            }
            try {
                $rules = [
                    'contract_number' => 'required',
                    'name'            => 'required',
                    'status'          => 'required',
                ];
                $this->validate($request, $rules);
                $row->fill($inputAll);
                $sign_date = new \DateTime($request->sign_date);
                $exp_date = new \DateTime($request->exp_date);
                $row->sign_date = $sign_date->format('Y-m-d H:i:s');
                $row->exp_date = $exp_date->format('Y-m-d H:i:s');
                if ($row->save()) {
                    if (!empty($request->image)) {
                        $newArr = [];
                        foreach ($request->image as $image) {
                            $newArr[] = [
                                'object_id'    => $row->id,
                                'object_model' => $row->type,
                                'file_id'      => $image,
                            ];
                        }
                        $row->mediaFiles()->sync($newArr);
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
            if ( !empty($ids) and is_array($ids)) {
                $this->partner->whereIn('id',$request->ids)->delete();
                return $this->sendSuccess([], 'Xóa thành công');
            }
            return $this->sendError('Không tìm thấy thông tin. Vui lòng thử lại');
        }
        public function export(Request $request)
        {
            try {
                $fileName = __('Quản lý đối tác :time.xlsx',['time'=>time()]);
                if (Storage::disk('export')->exists($fileName)) {
                    Storage::disk('export')->delete($fileName);
                }
                $export = (new PartnerExport($request))->store($fileName, 'export');
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
            if(!empty($request->file)){
                try {
                    (new PartnerImport($request))->import($request->file);
                    return $this->sendSuccess([],'Import thành công');
                } catch (\Exception $e) {
                    return $this->sendError($e->getMessage());
                }
            }

        }

    }

