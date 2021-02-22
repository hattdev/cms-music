<?php


    namespace Modules\Report\Models;


    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\SoftDeletes;
    use Modules\BaseModel;

    class Invoice extends BaseModel
    {
        use SoftDeletes;

        public    $type     = 'invoice';
        protected $table    = 'invoice';
        protected $appends  = ['contract_name', 'status_html', 'invoice_date_html'];
        protected $fillable = [
            'id',
            'name',
            'real_name',
            'invoice_start_date',
            'invoice_end_date',
            'invoice_type',
            'contract_number',
            'bank_account_number',
            'bank_name',
            'revenue_to_phuong_nam',
            'amount_payment_for_mg',
            'amount_payment_for_partner',

            'note',
            'files',
            'status',
            'create_user',
            'update_user',
        ];

        public function getInvoiceDateHtmlAttribute()
        {
            if (!empty($this->invoice_start_date) and !empty($this->invoice_end_date)) {
                return 'Từ '.date('d-m-Y', strtotime($this->invoice_start_date)).' đến '.date('d-m-Y', strtotime($this->invoice_end_date));
            }
        }

        public function getStatusHtmlAttribute()
        {
            $html = '';
            switch ($this->status) {
                case CHUA_THANH_TOAN:
                    $html = '<span class="badge p-2 badge-warning">Chưa thanh toán</span>';
                    break;
                case DA_THANH_TOAN:
                    $html = '<span class="badge p-2 badge-success ">Đã thanh toán</span>';
                    break;
                default:
                    $html = '<span class="badge p-2 badge-secondary">Nháp</span>';
                    break;
            }
            return $html;
        }

        public function search($request)
        {
            $query = self::newQuery();
            $filters = $request->queryParams['filters'] ?? [];
            $sorts = $request->queryParams['sort'] ?? [];
            if (!empty($filters)) {
                foreach ($filters as $filter) {
                    if ($filter['name'] == 'status_html') {
                        $query->whereIn('status', $filter['selected_options']);
                    }
                    if ($filter['name'] == 'contract_name') {
                        $query->whereHas('contract', function (Builder $query) use ($filter) {
                            $query->where('contract_number', 'like', '%'.$filter['text'].'%');
                        });
                    }
                    if ($filter['name'] == 'email') {
                        $query->whereHas('contract', function (Builder $query) use ($filter) {
                            $query->where('email', 'like', '%'.$filter['text'].'%');
                        });
                    }
                    if ($filter['name'] == 'phone') {
                        $query->whereHas('contract', function (Builder $query) use ($filter) {
                            $query->where('phone', 'like', '%'.$filter['text'].'%');
                        });
                    }
                    if ($filter['name'] == 'real_name') {
                        $query->where('real_name', 'like', '%'.$filter['text'].'%');
                    }
                    if ($filter['name'] == 'name') {
                        $query->where('name', 'like', '%'.$filter['text'].'%');
                    }
                    if ($filter['name'] == 'bank_account_number') {
                        $query->where('bank_account_number', 'like', '%'.$filter['text'].'%');
                    }

                }
            }
            if (!empty($sorts)) {
                foreach ($sorts as $sort) {
                    $query->orderBy($sort['name'], $sort['order']);
                }
            }
            if (!empty($request->invoice_type)) {
                $query->where('invoice_type', $request->invoice_type);
            };
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


    }
