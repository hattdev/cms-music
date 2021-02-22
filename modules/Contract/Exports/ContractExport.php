<?php


    namespace Modules\Contract\Exports;

    use Illuminate\Contracts\Support\Responsable;
    use Illuminate\Support\Arr;
    use Maatwebsite\Excel\Concerns\Exportable;
    use Maatwebsite\Excel\Concerns\FromCollection;
    use Maatwebsite\Excel\Concerns\WithHeadings;
    use Maatwebsite\Excel\Concerns\WithMapping;
    use Maatwebsite\Excel\Concerns\WithStyles;
    use Maatwebsite\Excel\Excel;
    use Modules\Contract\Models\Contract;
    use Modules\Media\Models\Media;
    use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


    class ContractExport implements FromCollection, Responsable, WithMapping, WithHeadings, WithStyles
    {

        use Exportable;

        /**
         * It's required to define the fileName within
         * the export class when making use of Responsable.
         */
        private $fileName = 'Quản lý hợp đồng.xlsx';

        /**
         * Optional Writer Type
         */
        private $writerType = Excel::XLSX;

        /**
         * Optional headers
         */
        private $headers = [
            'Content-Type' => 'text/csv',
        ];


        private $request;
        private $contract;

        public function __construct($request)
        {
            $this->request = $request;
            $this->contract = new Contract();
        }

        public function headings(): array
        {
            $columns = [
                'contract_number' => 'Số hợp đồng',
                'channel_name'    => "Tên kênh",
                'topic'           => "Chủ đề",
                'video_url'       => "Link video",
                'partner_name'    => 'Tên Đối tác',
                'sign_date'       => 'Ngày ký',
                'exp_date'        => 'Ngày hết hạn',
                'auto_renewed'    => 'Tự động gia hạn',
                'phone'           => 'Số Điện thoại',
                'email'           => 'Email',
                'share_rate'      => 'Tỷ lệ chia sẻ',
                'advance'         => "Tạm ứng",
                'items_provided'  => "Hạng mục cung cấp",
                'scope_of_supply' => "Phạm vi cung cấp",
                'status'          => 'Tình trạng hợp đồng',
                'file'            => 'files',
                'note'            => 'Chú ý',
            ];
            if($this->request->contract_type !=VIDEO){
                $columns = Arr::except($columns,['channel_name','topic','video_url']);
            }
            return $columns;
        }

        public function collection()
        {
            $request = $this->request;
            if (!empty($request)) {
                $query = (new Contract())->search($request);
                $rows = $query->get();
            } else {
                $rows = $this->contract->get();
            }
            return $rows;
        }

        /**
         * @inheritDoc
         */
        public function map($row): array
        {
            $fileUrls = [];
            if (!empty($row->files)) {
                $files = explode(',', $row->files);
                $getFiles = Media::whereIn('id', $files)->get();
                if (!empty($getFiles)) {
                    foreach ($getFiles as $file) {
                        $fileUrls[] = $file->url;
                    }
                }
            }
            $fileUrls = implode(',',$fileUrls);
            $data = [
                'contract_number' => $row->contract_number,
                'channel_name'    => $row->channel_name,
                'topic'           => $row->topic,
                'video_url'       => $row->video_url,
                'partner_name'    => $row->partner_name,
                'sign_date'       => $row->sign_date,
                'exp_date'        => $row->exp_date,
                'auto_renewed'    => $row->auto_renewed ? "Có" : "Không",
                'phone'           => $row->phone,
                'email'           => $row->email,
                'share_rate'      => $row->share_rate,
                'advance'         => $row->advance,
                'items_provided'  => $row->items_provided,
                'scope_of_supply' => $row->scope_of_supply,
                'status'          => $row->status_text,
                'file'            => $fileUrls,
                'note'            => $row->note,
            ];
            if($this->request->contract_type !=VIDEO){
                $data = Arr::except($data,['channel_name','topic','video_url']);
            }
            return $data;
        }

        /**
         * @inheritDoc
         */

        public function styles(Worksheet $sheet)
        {
            // TODO: Implement styles() method.
        }
    }