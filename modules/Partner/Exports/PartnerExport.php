<?php


    namespace Modules\Partner\Exports;

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
    use Modules\Partner\Models\Partner;
    use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


    class PartnerExport implements FromCollection, Responsable, WithMapping, WithHeadings, WithStyles
    {

        use Exportable;

        /**
         * It's required to define the fileName within
         * the export class when making use of Responsable.
         */
        private $fileName = 'Quản lý đối tác.xlsx';

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
        private $partner;

        public function __construct($request)
        {
            $this->request = $request;
            $this->partner = new Partner();
        }

        public function headings(): array
        {

            $columns = [
                'name'            => 'Tên Đối tác',
                'contract_number' => 'Số hợp đồng',
                'sign_date'       => 'Ngày ký',
                'exp_date'        => 'Ngày hết hạn',
                'auto_renewed'    => 'Tự động gia hạn',
                'tax_code'        => 'Mã số thuế',
                'phone'           => 'Số Điện thoại',
                'email'           => 'Email',
                'address'         => 'Địa chỉ',
                'share_rate'      => 'Tỷ lệ chia sẻ',
                'advance'         => "MG",
                'items_provided'  => "Hạng mục cung cấp",
                'scope_of_supply' => "Phạm vi cung cấp",
                'status'          => 'Tình trạng',
                'file'            => 'files',
                'note'            => 'Chú ý',
            ];

            return $columns;
        }

        public function collection()
        {
            $request = $this->request;
            if (!empty($request)) {
                $query = (new Partner())->search($request);
                $rows = $query->with('contract')->get();
            } else {
                $rows = $this->partner->with('contract')->get();
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
                'name'            => $row->name,
                'contract_number' => $row->contract_number,
                'sign_date'       => $row->contract->sign_date,
                'exp_date'        => $row->contract->exp_date,
                'auto_renewed'    => $row->contract->auto_renewed ? "Có" : "Không",
                'tax_code'        => $row->contract->tax_code,
                'phone'           => $row->contract->phone,
                'email'           => $row->contract->email,
                'address'         => $row->contract->address,
                'share_rate'      => $row->contract->share_rate,
                'advance'         => $row->contract->advance,
                'items_provided'  => $row->contract->items_provided,
                'scope_of_supply' => $row->contract->scope_of_supply,
                'status'          => $row->status_text,
                'file'            => $fileUrls,
                'note'            => $row->note,
            ];
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