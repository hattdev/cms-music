<?php


    namespace Modules\Partner\Imports;


    use Illuminate\Support\Arr;
    use Illuminate\Support\Str;
    use Maatwebsite\Excel\Concerns\Importable;
    use Maatwebsite\Excel\Concerns\ToModel;
    use Maatwebsite\Excel\Concerns\WithBatchInserts;
    use Maatwebsite\Excel\Concerns\WithChunkReading;
    use Maatwebsite\Excel\Concerns\WithHeadingRow;
    use Modules\Media\Models\Media;
    use Modules\Partner\Models\Partner;

    class PartnerImport implements ToModel,WithHeadingRow,WithChunkReading,WithBatchInserts
    {
        use Importable;

        /**
         * @inheritDoc
         */
        public function model(array $row)
        {
            $files = null;
            $status = !empty($row['trang_thai'])?Str::slug($row['trang_thai'],'_'):NHAP;

            if(!Arr::has(CONTRACT_STATUS,$status)){
                $status = NHAP;
            }
            if(!empty($row['danh_sach_files'])){
                $files = Media::importUploadFiles($row);
            }
            return new Partner([
                'contract_number'=>$row['so_hop_dong']??"",
                'name'=>$row['ten_doi_tac']??"",
                'note'=>$row["chu_y"]??"",
                'files'=>$files,
                'status'=>$status,
            ]);
            // TODO: Implement model() method.
        }
        public function headingRow(){
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