<?php


    namespace Modules\Partner\Models;


    use Illuminate\Database\Eloquent\SoftDeletes;
    use Modules\BaseModel;

    class Partner extends BaseModel
    {
        use SoftDeletes;

        public    $type     = 'doi_tac';
        protected $table    = 'partner';
        protected $appends  = ['contract_name', 'status_html'];
        protected $fillable = [
            'id',
            'contract_number',
            'name',
            'note',
            'status',
            'files',
            'create_user',
            'update_user',
        ];

        public function search($request)
        {
            $filters = $request->queryParams['filters'] ?? [];
            $sorts = $request->queryParams['sort'] ?? [];
            $query = self::newQuery();
            if (!empty($filters)) {
                foreach ($filters as $filter) {
                    if ($filter['name'] == 'status_html') {
                        if (Arr::first($filter['selected_options']) == SAP_HET_HAN) {
                            $query->where('exp_date', "<", date('Y-m-d H:i:s', strtotime('+2 month')))->where('exp_date', '>=', now())->where('status', CON_HAN);
                        } else {
                            $query->whereIn('status', $filter['selected_options']);
                        }
                    }
                    if ($filter['name'] == 'contract_name') {
                        $query->whereHas('contract', function (Builder $query) use ($filter) {
                            $query->where('contract_number', 'like', '%'.$filter['text'].'%');
                        });
                    }
                    if ($filter['name'] == 'tax_code') {
                        $query->whereHas('contract', function (Builder $query) use ($filter) {
                            $query->where('tax_code', 'like', '%'.$filter['text'].'%');
                        });
                    }
                    if ($filter['name'] == 'name') {
                        $query->where('name', 'like', '%'.$filter['text'].'%');
                    }
                    if ($filter['name'] == 'phone') {
                        $query->where('phone', 'like', '%'.$filter['text'].'%');
                    }
                    if ($filter['name'] == 'email') {
                        $query->where('email', 'like', '%'.$filter['text'].'%');
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
            $query->orderBy('id', 'desc')->with('contract');
            return $query;
        }
    }
