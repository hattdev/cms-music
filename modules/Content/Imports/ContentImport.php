<?php


    namespace Modules\Content\Imports;


    use Illuminate\Support\Arr;
    use Illuminate\Support\Str;
    use Maatwebsite\Excel\Concerns\Importable;
    use Maatwebsite\Excel\Concerns\ToModel;
    use Maatwebsite\Excel\Concerns\WithBatchInserts;
    use Maatwebsite\Excel\Concerns\WithChunkReading;
    use Maatwebsite\Excel\Concerns\WithHeadingRow;
    use Modules\Content\Models\BaiHatContent;
    use Modules\Content\Models\CaSiContent;
    use Modules\Content\Models\Content;
    use Modules\Content\Models\TacGiaContent;
    use Modules\Content\Models\VideoContent;
    use Modules\Media\Models\Media;

    class ContentImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts
    {
        use Importable;
        public $request;

        public function __construct($request)
        {
            $this->request = $request;

        }

        /**
         * @inheritDoc
         */
        public function model(array $row)
        {
            $sign_date = $exp_date = $files  = $fileMusics = $fileVideos = null;
            if (!empty($row['ngay_ky_noi_dung'])) {
                $sign_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['ngay_ky_noi_dung']);
            }
            if (!empty($row['ngay_het_han_noi_dung'])) {
                $exp_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['ngay_het_han_noi_dung']);
            }
            $status = !empty($row['trang_thai'])?Str::slug($row['trang_thai'],'_'):NHAP;
            if(!Arr::has(CONTRACT_STATUS,$status)){
                $status = NHAP;
            }
            $contentType = $this->request->content_type;
            if(!empty($row['danh_sach_files'])){
                $files = Media::importUploadFiles($row);
            }
            if(!empty($row['danh_sach_file_nhac'])){
                $fileMusics = Media::importUploadFiles($row,'danh_sach_file_nhac');
            }
            if(!empty($row['danh_sach_file_video'])){

            }
            $array = [
                'name'                => $row['ten'] ?? "",
                'channel_name'        => $row['ten_kenh'] ?? "",
                'singer_name'         => $row['ten_ca_si'] ?? "",
                'author_name'         => $row['ten_tac_gia'] ?? "",
                'author_lyric_name'   => $row['ten_tac_gia_loi'] ?? "",
                'one_permission'      => $row['1_quyen'] ?? "",
                'full_permission'     => $row['du_quyen'] ?? "",
                'monopoly_permission' => $row['doc_quyen'] ?? "",
                'contract_number'     => $row['so_hop_dong'] ?? "",
                'content_type'        => $this->request->content_type,
                'sub_contract_number' => $row['so_phu_luc_hop_dong'] ?? "",
                'sub_contract_order'  => $row['so_tt_phu_luc'] ?? "",
                'lyric_song'          => $row['loi_bai_hat'] ?? "",
                'sign_date'           => $sign_date,
                'exp_date'            => $exp_date,
                'note'                => $row['chu_y'] ?? "",
                'video_url'           => $row['link_video'] ?? "",
                'topic'               => $row['chu_de'] ?? "",
                'files'           => $files,
                'music_files'           => $fileMusics,
                'video_files'           => $fileVideos,
                'status'              => $status,
            ];
            switch ($contentType){
                case VIDEO:
                    return  new VideoContent($array);
                    break;
                case TAC_GIA:
                    return new TacGiaContent($array);
                    break;
                case CA_SI:
                    return  new CaSiContent($array);
                    break;
                default:
                    return new BaiHatContent($array);
                    break;
            }

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