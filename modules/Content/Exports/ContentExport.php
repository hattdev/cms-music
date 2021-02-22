<?php


    namespace Modules\Content\Exports;

    use Illuminate\Contracts\Support\Responsable;
    use Illuminate\Support\Arr;
    use Maatwebsite\Excel\Concerns\Exportable;
    use Maatwebsite\Excel\Concerns\FromCollection;
    use Maatwebsite\Excel\Concerns\WithHeadings;
    use Maatwebsite\Excel\Concerns\WithMapping;
    use Maatwebsite\Excel\Concerns\WithStyles;
    use Maatwebsite\Excel\Excel;
    use Modules\Content\Models\Content;
    use Modules\Media\Models\Media;
    use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


    class ContentExport implements FromCollection, Responsable, WithMapping, WithHeadings, WithStyles
    {

        use Exportable;

        /**
         * It's required to define the fileName within
         * the export class when making use of Responsable.
         */
        private $fileName = 'Quản lý nội dung.xlsx';

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
        private $content;

        public function __construct($request)
        {
            $this->request = $request;
            $this->content = new Content();
        }

        public function headings(): array
        {
            $columns  = [
                'name'                => "Tên",
                'channel_name'        => 'Tên kênh',
                'topic'               => "Chủ đề",
                'video_url'           => "Video url",
                'singer_name'         => "Tên ca sĩ",
                'author_name'         => "Tên tác giả",
                'author_lyric_name'   => "Tên tác giả viết lời",
                'one_permission'      => '1 Quyền',
                'full_permission'     => 'Đủ quyền',
                'monopoly_permission' => "Độc quyền",
                'contract_number'     => 'Số Hợp đồng',
                'sub_contract_name'   => 'Mã Phụ lục',
                'sub_contract_order'  => "Số TT phụ lục",
                'sign_date'           => "Ngày ký",
                'exp_date'            => "Ngày hết hạn",
                'auto_renewed'   => "Tự động gia hạn",
                'items_provided'      => "Hạng mục cung cấp",
                'scope_of_supply'     => "Phạm vi cung cấp",
                'status'         => "Trạng thái",
                'lyric_song'          => "Lời bài hát",
                'note'          => "Chú ý",

            ];
            if ($this->request->content_type == BAI_HAT) {
                $columns = Arr::except($columns, ['author_lyric_name', 'author_name', 'channel_name', 'topic', 'video_url']);
            }
            if ($this->request->content_type == VIDEO) {
                $columns = Arr::except($columns, ['author_lyric_name', 'author_name', 'singer_name', 'items_provided','lyric_song']);
            }
            if ($this->request->content_type == TAC_GIA) {
                $columns = Arr::except($columns, ['topic', 'video_url', 'channel_name']);
            }
            return $columns;
        }

        public function collection()
        {
            $request = $this->request;
            if (!empty($request)) {
                $query = (new Content())->search($request);
                $rows = $query->with(['contract', 'subContract'])->get();
            } else {
                $rows = $this->content->newQuery()->with(['contract', 'subContract'])->get();
            }
            return $rows;
        }

        /**
         * @inheritDoc
         */
        public function map($row): array
        {
            $fileUrls = $musicUrls = $videoUrls = [];
            if (!empty($row->files)) {
                $files = explode(',', $row->files);
                $getFiles = Media::whereIn('id', $files)->get();
                if (!empty($getFiles)) {
                    foreach ($getFiles as $file) {
                        $fileUrls[] = $file->url;
                    }
                }
            }
            if (!empty($row->music_files)) {
                $files = explode(',', $row->music_files);
                $getFiles = Media::whereIn('id', $files)->get();
                if (!empty($getFiles)) {
                    foreach ($getFiles as $file) {
                        $musicUrls[] = $file->url;
                    }
                }
            }
            if (!empty($row->video_files)) {
                $files = explode(',', $row->video_files);
                $getFiles = Media::whereIn('id', $files)->get();
                if (!empty($getFiles)) {
                    foreach ($getFiles as $file) {
                        $videoUrls[] = $file->url;
                    }
                }
            }
            $fileUrls = implode(',',$fileUrls);
            $musicUrls = implode(',',$musicUrls);
            $videoUrls = implode(',',$videoUrls);
            $data = [
                'name'                => $row->name,
                'singer_name'         => $row->singer_name,
                'author_name'         => $row->author_name,
                'author_lyric_name'   => $row->author_lyric_name,
                'topic'               => $row->topic,
                'channel_name'        => $row->channel_name,
                'video_url'           => $row->video_url,
                'one_permission'      => $row->one_permission ? "X" : "",
                'full_permission'     => $row->full_permission ? "X" : "",
                'monopoly_permission' => $row->monopoly_permission ? "X" : "",
                'contract_name'       => $row->contract->contract_number,
                'sub_contract_name'   => $row->sub_contract_name,
                'sub_contract_order'  => $row->sub_contract_order,
                'sign_date'           => $row->sign_date,
                'exp_date'            => $row->exp_date,
                'auto_renewed'        => $row->contract->auto_renewed ? "Có" : "Không",
                'items_provided'      => $row->contract->items_provided,
                'scope_of_supply'     => $row->contract->scope_of_supply,
                'status'              => $row->status_text,
                'file'            => $fileUrls,
                'music_files'            => $musicUrls,
                'video_files'            => $videoUrls,
                'lyric_song'          => $row->lyric_song,
                'note'          => $row->note,
            ];
            if ($this->request->content_type == BAI_HAT) {
                $data = Arr::except($data, ['author_lyric_name', 'author_name', 'channel_name', 'topic', 'video_url']);
            }
            if ($this->request->content_type == VIDEO) {
                $data = Arr::except($data, ['author_lyric_name', 'author_name', 'singer_name', 'items_provided','lyric_song']);
            }
            if ($this->request->content_type == TAC_GIA) {
                $data = Arr::except($data, ['topic', 'video_url', 'channel_name']);
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
