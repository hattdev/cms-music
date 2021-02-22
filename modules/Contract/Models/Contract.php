<?php


    namespace Modules\Contract\Models;


    use Illuminate\Database\Eloquent\SoftDeletes;
    use Illuminate\Support\Arr;
    use Modules\BaseModel;

    class Contract extends BaseModel
    {
        use SoftDeletes;

        public    $type    = 'hop_dong';
        protected $table   = 'contract';
        protected $appends = ['contract_type_html', 'status_html', 'auto_renewed_html'];

        protected $fillable = [
            'id',
            'contract_type',
            'contract_name',
            'contract_number',
            'sign_date',
            'exp_date',
            'auto_renewed',
            'share_rate',
            'advance',
            'items_provided',
            'scope_of_supply',

            'partner_name',
            'tax_code',
            'phone',
            'email',
            'address',
            'cmnd',
            'note',

            'video_url',
            'topic',
            'tax_code',
            'channel_name',
            'files',
            'status',
            'create_user',
            'update_user',
        ];
        protected $dates = ['deleted_at'];

        public function search($request){
            $query = self::query();
            if (!empty($request->contract_type)) {
                $query->where('contract_type', $request->contract_type);
            }
            $filters = $request->queryParams['filters'] ?? [];
            $sorts = $request->queryParams['sort'] ?? [];
            if (!empty($filters)) {
                foreach ($filters as $filter) {
                    if ($filter['name'] == 'status_html') {
                        if (Arr::first($filter['selected_options']) == SAP_HET_HAN) {
                            $query->where('exp_date', "<", date('Y-m-d', strtotime('+2 month')).' 00:00:00')->where('exp_date', '>=', date('Y-m-d').' 23:59:59')->where('status', CON_HAN);
                        } elseif (Arr::first($filter['selected_options'])==HET_HAN){
                            $query->where('exp_date', "<", date('Y-m-d H:i:s'))->orWhere('status', HET_HAN);
                        }else {
                            $query->whereIn('status', $filter['selected_options']);
                        }
                    }
                    if ($filter['name'] == 'contract_number') {
                        $query->where('contract_number', 'like', '%'.$filter['text'].'%');
                    }
                    if ($filter['name'] == 'partner_name') {
                        $query->where('partner_name', 'like', '%'.$filter['text'].'%');
                    }
                    if ($filter['name'] == 'phone') {
                        $query->where('phone', 'like', '%'.$filter['text'].'%');
                    }
                    if ($filter['name'] == 'email') {
                        $query->where('email', 'like', '%'.$filter['text'].'%');
                    }
                }
            }
            if(!empty($request->selectedItems)){
                $query->whereIn('id',$request->selectedItems);
            }
            if (!empty($sorts)) {
                foreach ($sorts as $sort) {
                    $query->orderBy($sort['name'], $sort['order']);
                }
            }
            $query->orderBy('id', 'desc');
            return $query;
        }

    }
