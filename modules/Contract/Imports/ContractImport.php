<?php


    namespace Modules\Contract\Imports;


    use Illuminate\Support\Arr;
    use Illuminate\Support\Str;
    use Maatwebsite\Excel\Concerns\Importable;
    use Maatwebsite\Excel\Concerns\ToModel;
    use Maatwebsite\Excel\Concerns\WithBatchInserts;
    use Maatwebsite\Excel\Concerns\WithChunkReading;
    use Maatwebsite\Excel\Concerns\WithHeadingRow;
    use Modules\Contract\Models\Contract;
    use Modules\Media\Helpers\FileHelper;
    use Modules\Media\Models\Media;

    class ContractImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts
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
            $sign_date = $exp_date = $files =null;

            if (!empty($row['ngay_ky'])) {
                $sign_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['ngay_ky']);
            }
            if (!empty($row['ngay_het_han'])) {
                $exp_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['ngay_het_han']);
            }
            $status = !empty($row['trang_thai']) ? Str::slug($row['trang_thai'], '_') : NHAP;

            if (!Arr::has(CONTRACT_STATUS, $status)) {
                $status = NHAP;
            }

            if(!empty($row['danh_sach_files'])){
                $files = Media::importUploadFiles($row);
            }

            return new Contract([
                'contract_type'   => $this->request->contract_type,
                'contract_name'   => $row['ten_hop_dong'] ?? "",
                'contract_number' => $row['so_hop_dong'] ?? "",
                'sign_date'       => $sign_date,
                'exp_date'        => $exp_date,
                'auto_renewed'    => $row['tu_dong_gia_han'] ? 1 : 0,
                'share_rate'      => $row['ty_le_chia_se'] ? (int) $row['ty_le_chia_se'] : 0,
                'advance'         => $row['tam_ung'] ? (int) $row['tam_ung'] : 0,
                'items_provided'  => $row['hang_muc_cung_cap'] ?? "",
                'scope_of_supply' => $row['pham_vi_cung_cap'] ?? "",
                'partner_name'    => $row['ten_doi_tac'] ?? "",
                'tax_code'        => $row['ma_so_thue'] ?? "",
                'phone'           => $row['so_dien_thoai'] ?? "",
                'email'           => $row['email'] ?? "",
                'address'         => $row['dia_chi'] ?? "",
                'cmnd'            => $row['so_cmnd'] ?? "",
                'note'            => $row['chu_y'] ?? "",
                'video_url'       => $row['link_video'] ?? "",
                'topic'           => $row['chu_de'] ?? "",
                'channel_name'    => $row['ten_kenh'] ?? "",
                'files'           => $files,
                'status'          => $status,
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