<?php


    namespace Modules\Contract\Controllers;


    use Illuminate\Http\Request;
    use Illuminate\Support\Arr;
    use Illuminate\Support\Facades\Storage;
    use Modules\Contract\Exports\ContractExport;
    use Modules\Contract\Imports\ContractImport;
    use Modules\Contract\Models\Contract;
    use Modules\ModuleController;
    use Modules\Partner\Imports\PartnerImport;

    class ContractController extends ModuleController
    {
        /**
         * @var Contract
         */
        private $contract;

        /**
         * @var string
         */

        public function __construct(Contract $contract)
        {
            parent::__construct();
            $this->contract = $contract;
        }

        public function search(Request $request)
        {
            $per_page = $request->queryParams['per_page'] ?? LIMIT;
            $query = $this->contract->search($request);
            $rows = $query->paginate($per_page);
            $data['total_rows'] = $rows->total();
            $data['rows'] = $rows->items();
            $columns = [
                'id'                => "id",
                'channel_name'      => "Tên kênh",
                'topic'             => "Chủ đề",
                'video_url'         => "Link video",
                'contract_number'   => 'Số Hợp đồng',
                'partner_name'      => 'Đối tác',
                'sign_date'         => "Ngày ký",
                'exp_date'          => "Ngày hết hạn",
                'auto_renewed_html' => "Tự động gia hạn",
                //            'phone'=>"Số Điện Thoại",
                //            'email'=>"Email",
                'share_rate'        => "Tỷ lệ chia sẻ",
                'advance'           => "Tạm ứng",
                'items_provided'    => "Hạng mục cung cấp",
                'scope_of_supply'   => "Phạm vi cung cấp",
                'status_html'       => "Trạng thái",
                'action'            => "Hành động"
            ];
            $filtering = [
                'contract_number',
                'partner_name',
                'phone',
                'email',
            ];

            if ($request->contract_type != 'video') {
                $columns = Arr::except($columns, ['channel_name', "video_url", "topic"]);
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
                            'placeholder' => 'Chọn',
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

        public function select2(Request $request)
        {
            $q = $request->search;
            $first = $request->first;
            if (!empty($q)) {
                $query = $this->contract;
                if (!$first) {
                    $rows = $query->orWhere('contract_name', 'like', '%'.$q.'%')->orWhere('contract_number', 'like', '%'.$q.'%')->get(['contract_number as label', 'id']);
                } else {
                    $rows = $query->where('contract_number', $q)->select(['*', 'contract_number as label'])->first();
                }
                if (!empty($rows)) {
                    $rows = $rows->makeHidden(['contract_type_html',  'auto_renewed_html'])->toArray();
                    return $this->sendSuccess(['data' => $rows]);
                }
            }
            return $this->sendError('Không có dữ liệu');

        }

        public function edit(Request $request, $id)
        {
            $contract = $this->contract->find($id);
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
            if (!$id) {
                $contract = $this->contract;
            } else {
                $contract = $this->contract->find($id);
            }
            $rules = [
                'contract_type'   => 'required',
                'contract_number' => 'required',
                'sign_date'       => 'required',
                'exp_date'        => 'required',
//                'auto_renewed'    => 'required',
                //                'name' => 'required',
                'phone'           => 'required',
                'email'           => 'required',
                'share_rate',
                'advance',
                'items_provided',
                'scope_of_supply',
                'status'          => 'required',
                'create_user',
                'update_user',
            ];
            $validation = $request->validate($rules);

            $contract->fill($inputAll);
            $sign_date = new \DateTime($request->sign_date);
            $exp_date = new \DateTime($request->exp_date);
            $contract->sign_date = $sign_date->format('Y-m-d H:i:s');
            $contract->exp_date = $exp_date->format('Y-m-d H:i:s');
            if ($contract->save()) {
                if (!empty($request->image)) {
                    $newArr = [];
                    foreach ($request->image as $image) {
                        $newArr[] = [
                            'object_id'    => $contract->id,
                            'object_model' => $contract->type,
                            'file_id'      => $image,
                        ];
                    }
                    $contract->mediaFiles()->sync($newArr);
                }
            }
            return response()->json(['status' => 1, 'message' => 'Thành công']);


        }

        public function delete(Request $request)
        {
            $ids = $request->ids;
            if (!empty($ids) and is_array($ids)) {
                $this->contract->whereIn('id', $request->ids)->delete();
                return $this->sendSuccess([], 'Xóa thành công');
            }
            return $this->sendError('Không tìm thấy thông tin. Vui lòng thử lại');
        }

        public function export(Request $request)
        {
            try {
                $fileName = __('Quản lý hợp đồng :time.xlsx',['time'=>time()]);

                if (Storage::disk('export')->exists($fileName)) {
                    Storage::disk('export')->delete($fileName);
                }
                $export = (new ContractExport($request))->store($fileName, 'export');
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
                    (new ContractImport($request))->import($request->file);
                    return $this->sendSuccess([],'Import thành công');
                } catch (\Exception $e) {
                    return $this->sendError($e->getMessage());
                }
            }

        }

    }

