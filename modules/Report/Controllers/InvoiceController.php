<?php


    namespace Modules\Report\Controllers;


    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Http\Request;
    use Illuminate\Support\Arr;
    use Illuminate\Support\Facades\Storage;
    use Modules\ModuleController;
    use Modules\Report\Exports\InvoiceExport;
    use Modules\Report\Imports\InvoiceImport;
    use Modules\Report\Models\Invoice;

    class InvoiceController extends ModuleController
    {

        /**
         * @var Invoice
         */
        private $invoice;


        public function __construct(Invoice $invoice)
        {
            parent::__construct();
            $this->invoice = $invoice;
        }

        public function search(Request $request)
        {


            $per_page = $request->queryParams['per_page'] ?? LIMIT;
            $query = $this->invoice->search($request);
            $rows = $query->with('contract')->paginate($per_page);
            $rows->map(function ($item) {
                $item->phone = $item->contract->phone;
                $item->email = $item->contract->email;
                $item->share_rate = $item->contract->share_rate;
                return $item;
            });
            $data['total_rows'] = $rows->total();
            $data['rows'] = $rows->items();

            $columns = [
                'id'                         => "id",
                'invoice_date_html'          => "Kỳ đối soát",
                'name'                       => "Tên",
                'real_name'                       => "Tên thật",
                'phone'                      => 'Số điện thoại',
                'email'                      => 'Email',
                'contract_name'              => 'Số Hợp đồng',
                'bank_account_number'        => "Số tài khoản",
                'bank_name'                  => "Ngân hàng",
                'share_rate'                 => "Tỷ lệ chia sẻ",
                'revenue_to_phuong_nam'      => 'Doanh thu PN',
                'amount_payment_for_mg'      => "Tạm ứng",
                'amount_payment_for_partner' => "TT cho đối tác",
                'status_html'                => "Trạng thái",
                'action'                     => "Hành động",
            ];
            $filtering = [
                'name',
                'real_name',
                'phone',
                'email',
                'contract_name',
                'bank_account_number',
            ];
            if ($request->invoice_type == INVOICE_TYPE_COMPANY) {
                $columns = Arr::except($columns, ['real_name']);
                $filtering = Arr::except($filtering, ['real_name']);
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
                                    'name'  => INVOICE_STATUS[NHAP],
                                    'value' => NHAP,
                                ],
                                [
                                    'name'  => INVOICE_STATUS[CHUA_THANH_TOAN],
                                    'value' => CHUA_THANH_TOAN,
                                ],
                                [
                                    'name'  => INVOICE_STATUS[DA_THANH_TOAN],
                                    'value' => DA_THANH_TOAN,
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
            $row = $this->invoice->find($id);
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
                $contract = $this->invoice;
            } else {
                $contract = $this->invoice->find($id);
            }
            try {
                $rules = [
                    'contract_number'    => 'required',
                    'invoice_end_date'   => 'required',
                    'invoice_start_date' => 'required',
                ];
                $this->validate($request, $rules);
                $contract->fill($inputAll);
//                $sign_date = new \DateTime($request->sign_date);
//                $exp_date = new \DateTime($request->exp_date);
//                $contract->sign_date = $sign_date->format('Y-m-d H:i:s');
//                $contract->exp_date = $exp_date->format('Y-m-d H:i:s');
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
            } catch (\Exception $exception) {
                return response()->json(['status' => 0, 'message' => $exception->getMessage()]);
            }
        }

        public function delete(Request $request)
        {
            $ids = $request->ids;
            if (!empty($ids) and is_array($ids)) {
                $this->invoice->whereIn('id', $request->ids)->delete();
                return $this->sendSuccess([], 'Xóa thành công');
            }
            return $this->sendError('Không tìm thấy thông tin. Vui lòng thử lại');
        }
        public function export(Request $request)
        {
            try {
                $fileName = __('Quản lý đối soát thanh toán :time.xlsx', ['time' => time()]);
                if (Storage::disk('export')->exists($fileName)) {
                    Storage::disk('export')->delete($fileName);
                }
                $export = (new InvoiceExport($request))->store($fileName, 'export');
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
                   (new InvoiceImport($request))->import($request->file);
                    return $this->sendSuccess([],'Import thành công');
                } catch (\Exception $e) {
                    return $this->sendError($e->getMessage());
                }
            }

        }
    }

