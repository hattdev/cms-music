<?php


    namespace Modules\Report\Exports;

    use Illuminate\Contracts\Support\Responsable;
    use Maatwebsite\Excel\Concerns\Exportable;
    use Maatwebsite\Excel\Concerns\FromCollection;
    use Maatwebsite\Excel\Concerns\WithHeadings;
    use Maatwebsite\Excel\Concerns\WithMapping;
    use Maatwebsite\Excel\Concerns\WithStyles;
    use Maatwebsite\Excel\Excel;
    use Modules\Media\Models\Media;
    use Modules\Report\Models\Invoice;
    use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


    class InvoiceExport implements FromCollection, Responsable, WithMapping, WithHeadings, WithStyles
    {

        use Exportable;

        /**
         * It's required to define the fileName within
         * the export class when making use of Responsable.
         */
        private $fileName = 'Quản lý đối soát thanh toán.xlsx';

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
        private $invoice;

        public function __construct($request)
        {
            $this->request = $request;
            $this->invoice = new Invoice();
        }

        public function headings(): array
        {
            $columns = [
                'name'                       => "Tên",
                'bank_account_number'        => "Số Tài Khoản",
                'bank_name'                  => "Ngân hàng",
                'share_rate'                 => 'Tỷ lệ chia sẻ',
                'amount_payment_for_mg'      => "Chi Phí MG",
                'contract_number'            => 'Số Hợp đồng',
                'sign_date'                  => "Ngày ký",
                'exp_date'                   => "Ngày hết hạn",
                'invoice_start_date'         => 'Đối soát từ ngày',
                'invoice_end_date'           => "Đến ngày",
                'revenue_to_phuong_nam'      => "Doanh thu PN",
                'amount_payment_for_partner' => "Số tiền TT cho TG/CS (VAT)",
                'files',


            ];
//            if ($this->request->content_type == BAI_HAT) {
//                $columns = Arr::except($columns, ['author_lyric_name', 'author_name', 'channel_name', 'topic', 'video_url']);
//            }
//            if ($this->request->content_type == VIDEO) {
//                $columns = Arr::except($columns, ['author_lyric_name', 'author_name', 'singer_name', 'items_provided', 'lyric_song']);
//            }
//            if ($this->request->content_type == TAC_GIA) {
//                $columns = Arr::except($columns, ['topic', 'video_url', 'channel_name']);
//            }
            return $columns;
        }

        public function collection()
        {
            $request = $this->request;
            if (!empty($request)) {
                $query = (new Invoice())->search($request);
                $rows = $query->with(['contract'])->get();
            } else {
                $rows = $this->invoice->newQuery()->with(['contract', 'subContract'])->get();
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
                'name'                       => $row->name,
                'bank_account_number'        => $row->bank_account_number,
                'bank_name'                  => $row->bank_name,
                'share_rate'                 => $row->contract->share_rate,
                'amount_payment_for_mg'      => $row->amount_payment_for_mg,
                'contract_number'            => $row->contract->contract_number,
                'sign_date'                  => $row->sign_date,
                'exp_date'                   => $row->exp_date,
                'invoice_start_date'         => $row->invoice_start_date,
                'invoice_end_date'           => $row->invoice_end_date,
                'revenue_to_phuong_nam'      => $row->revenue_to_phuong_nam,
                'amount_payment_for_partner' => $row->amount_payment_for_partner,
                'files'                      => $fileUrls

            ];
//            if ($this->request->content_type == BAI_HAT) {
//                $data = Arr::except($data, ['author_lyric_name', 'author_name', 'channel_name', 'topic', 'video_url']);
//            }
//            if ($this->request->content_type == VIDEO) {
//                $data = Arr::except($data, ['author_lyric_name', 'author_name', 'singer_name', 'items_provided', 'lyric_song']);
//            }
//            if ($this->request->content_type == TAC_GIA) {
//                $data = Arr::except($data, ['topic', 'video_url', 'channel_name']);
//            }
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
