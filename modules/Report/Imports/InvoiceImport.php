<?php


    namespace Modules\Report\Imports;


    use Illuminate\Support\Arr;
    use Illuminate\Support\Str;
    use Maatwebsite\Excel\Concerns\Importable;
    use Maatwebsite\Excel\Concerns\ToModel;
    use Maatwebsite\Excel\Concerns\WithBatchInserts;
    use Maatwebsite\Excel\Concerns\WithChunkReading;
    use Maatwebsite\Excel\Concerns\WithHeadingRow;
    use Modules\Media\Models\Media;
    use Modules\Report\Models\Invoice;

    class InvoiceImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts
    {
        use Importable;
        private $request;

        public function __construct($request)
        {
            $this->request = $request;

        }

        /**
         * @inheritDoc
         */
        public function model(array $row)
        {
            $invoice_sign_date = $invoice_exp_date = $files = null;
            if (!empty($row['doi_soat_tu_ngay'])) {
                $invoice_sign_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['doi_soat_tu_ngay']);
            }
            if (!empty($row['doi_soat_den_ngay'])) {
                $invoice_exp_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['doi_soat_den_ngay']);
            }
//            $status = !empty($row['trang_thai']) ? Str::slug($row['trang_thai'], '_') : NHAP;

//            if (!Arr::has(INVOICE_STATUS, $status)) {
//                $status = NHAP;
//            }
            if (!empty($row['danh_sach_files'])) {
                $files = Media::importUploadFiles($row);
            }
            $name = $row['ten_ca_sy_tac_gia'] ?? "";
            if (!empty($row['ten_doi_tac'])) {
                $name = $row['ten_doi_tac'];
            }
            return new Invoice([
                'name'                       => $name,
                'real_name'                  => $row['ten_that']??'',
                'invoice_type'               => $this->request->invoice_type,
                'contract_number'            => $row['so_hop_dong'] ?? "",
                'invoice_start_date'         => $invoice_sign_date,
                'invoice_end_date'           => $invoice_exp_date,
                'bank_account_number'        => $row['so_tai_khoan'] ?? '',
                'bank_name'                  => $row['ngan_hang'] ?? '',
                'revenue_to_phuong_nam'      => (int) $row['doanh_thu_pn'] ?? 0,
                'amount_payment_for_mg'      => (int) $row['chi_phi_mg'] ?? 0,
                'amount_payment_for_partner' => (int) $row['so_tien_tt_cho_tgcs_vat'] ?? 0,
                'advance'                    => $row['tam_ung'] ? (int) $row['tam_ung'] : 0,
                'note'                       => $row['chu_y'] ?? '',
                'files'                      => $files,
            ]);
            // TODO: Implement model() method.
        }

        public function headingRow()
        {
            return 1;
        }

        /**
         * @inheritDoc
         */
        public function chunkSize(): int
        {
            return 500;
            // TODO: Implement chunkSize() method.
        }

        /**
         * @inheritDoc
         */
        public function batchSize(): int
        {
            return 200;
            // TODO: Implement batchSize() method.
        }
    }