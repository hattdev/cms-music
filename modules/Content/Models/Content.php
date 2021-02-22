<?php


    namespace Modules\Content\Models;


    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\SoftDeletes;
    use Illuminate\Support\Arr;
    use Modules\BaseModel;

    class Content extends BaseModel
    {
        use SoftDeletes;

        public    $type     = 'noi_dung';
        protected $table    = 'content';
        protected $appends  = ['contract_name','sub_contract_name', 'status_html'];
        protected $fillable = [
            'id',
            'channel_name',
            'singer_name',
            'author_name',
            'author_lyric_name',
            'one_permission',
            'full_permission',
            'monopoly_permission',
            'contract_number',
            'content_type',
            'sub_contract_number',
            'sub_contract_order',
            'lyric_song',
            'sign_date',
            'exp_date',
            'name',
            'note',
            'files',
            'music_files',
            'video_files',
            'video_url',
            'topic',
            'status',
            'create_user',
            'update_user',
        ];


        public function __construct(array $attributes = [])
        {
            parent::__construct($attributes);
        }

        public static function getModelByContentType($contentType=BAI_HAT){
            switch ($contentType){
                case VIDEO:
                    $model = new VideoContent();
                    break;
                case TAC_GIA:
                    $model = new TacGiaContent();
                    break;
                case CA_SI:
                    $model = new CaSiContent();
                    break;
                default:
                    $model = new BaiHatContent();
                    break;
            }
            return $model;
        }
        public function search($request)
        {
            $contentType = $request->content_type;
            $query = self::getModelByContentType($contentType)->query();
            $filters = $request->queryParams['filters'] ?? [];
            $sorts = $request->queryParams['sort'] ?? [];
            if (!empty($filters)) {
                foreach ($filters as $filter) {
                    if ($filter['name'] == 'status_html') {
                        if (Arr::first($filter['selected_options']) == SAP_HET_HAN) {
                            $query->whereHas('contract',function(Builder $queryContract){
                                $queryContract->where('exp_date', "<", date('Y-m-d', strtotime('+2 month')).' 00:00:00')->where('exp_date', '>=', date('Y-m-d').' 23:59:59')->where('status', CON_HAN);
                            });
                        } elseif (Arr::first($filter['selected_options'])==HET_HAN){
                            $query->whereHas('contract',function(Builder $queryContract){
                                $queryContract->where('exp_date', "<", date('Y-m-d H:i:s'))->orWhere('status', HET_HAN);
                            });

                        }else {
                            $query->whereHas('contract',function(Builder $queryContract) use($filter){
                                $queryContract->whereIn('status', $filter['selected_options']);
                            });
                        }
                    }
                    if ($filter['name'] == 'contract_name') {
                        $query->whereHas('contract', function (Builder $queryContract) use ($filter) {
                            $queryContract->where('contract_number', 'like', '%'.$filter['text'].'%');
                        });
                    }
                    if ($filter['name'] == 'sub_contract_number') {
                        $query->whereHas('subContract', function (Builder $queryContract) use ($filter) {
                            $queryContract->where('contract_number', 'like', '%'.$filter['text'].'%');
                        });
                    }
                    if ($filter['name'] == 'name') {
                        $query->where('name', 'like', '%'.$filter['text'].'%');
                    }
                    if ($filter['name'] == 'channel_name') {
                        $query->where('channel_name', 'like', '%'.$filter['text'].'%');
                    }
                    if ($filter['name'] == 'singer_name') {
                        $query->where('singer_name', 'like', '%'.$filter['text'].'%');
                    }
                    if ($filter['name'] == 'author_name') {
                        $query->where('author_name', 'like', '%'.$filter['text'].'%');
                    }
                    if ($filter['name'] == 'items_provided') {
                        $query->where('items_provided', 'like', '%'.$filter['text'].'%');
                    }
                    if ($filter['name'] == 'scope_of_supply') {
                        $query->where('scope_of_supply', 'like', '%'.$filter['text'].'%');
                    }
                }
            }
            if (!empty($request->selectedItems)) {
                $query->whereIn('id', $request->selectedItems);
            }
            if (!empty($sorts)) {
                foreach ($sorts as $sort) {
                    $query->orderBy($sort['name'], $sort['order']);
                }
            }
            $query->orderBy('id', 'desc');
            return $query;

        }
        public function getStatusHtmlAttribute()
        {

            $html = '';
            if(!empty($this->contract)){
                $status = $this->contract->status??NHAP;
                $expDate = $this->contract->exp_date;
                switch ($status) {
                    case "con_han":
                        if (strtotime($expDate) < strtotime('+2 month') and strtotime($expDate) > time()) {
                            $html = '<span class="badge p-1 badge-warning">Sắp hết hạn</span>';
                        } elseif (strtotime($expDate) <= time()) {
                            $html = '<span class="badge p-1 badge-danger">Hết Hạn</span>';
                        } else {
                            $html = '<span class="badge p-1 badge-success">Còn hạn</span>';
                        }
                        break;
                    case "het_han":
                        $html = '<span class="badge p-1 badge-danger">Hết hạn</span>';
                        break;
                    case "da_thanh_ly":
                        $html = '<span class="badge p-1 badge-dark ">Đã thanh lý</span>';
                        break;
                    default:
                        $html = '<span class="badge p-1 badge-secondary">Nháp</span>';
                        break;
                }
            }else{
                $html = '<span class="badge p-1 badge-secondary">Nháp</span>';
            }

            return $html;
        }


    }
