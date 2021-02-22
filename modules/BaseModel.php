<?php


    namespace Modules;


    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Facades\Cache;
    use Modules\Contract\Models\Contract;
    use Modules\Media\Models\Media;
    use Modules\Media\Models\MediaRelation;

    class BaseModel extends Model
    {

        public function contract()
        {
            return $this->belongsTo(Contract::class, 'contract_number','contract_number')->withDefault();
        }

        public function subContract()
        {
            return $this->belongsTo(Contract::class, 'sub_contract_number','contract_number')->withDefault();
        }

        public function mediaFiles()
        {
            return $this->belongsToMany(Media::class, (new MediaRelation)->getTable(), 'object_id', 'file_id')
                ->where('object_model', $this->type);
        }

        public function findById($id)
        {
            return Cache::rememberForever($this->cacheKey().':'.$id, function () use ($id) {
                return $this->find($id);
            });
        }

        public function getContractTypeHtmlAttribute()
        {
            return CONTRACT_TYPE[$this->content_type] ?? '';
        }

        public function getContractNameAttribute($val)
        {
            if (!empty($this->contract_number) and $this->type != 'hop_dong') {
                return $this->contract->contract_number;
            } else {
                return $val;
            }
        }

        public function getSubContractNameAttribute()
        {
            return $this->subContract->contract_number;
        }

        public function getStatusHtmlAttribute()
        {

            $html = '';
            switch ($this->status) {
                case "con_han":
                    if (strtotime($this->exp_date) < strtotime('+2 month') and strtotime($this->exp_date) > time()) {
                        $html = '<span class="badge p-1 badge-warning">Sắp hết hạn</span>';
                    } elseif (strtotime($this->exp_date) <= time()) {
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
            return $html;
        }

        public function getStatusTextAttribute()
        {
            $text = '';
            switch ($this->status) {
                case "con_han":
                    $text = 'Còn hạn';
                    if (!empty($this->exp_date)) {
                        if (strtotime($this->exp_date) <= strtotime('+1 month')) {
                            $text = 'Sắp hết hạn';
                        }
                    } else {
                        if (!empty($contractExp = $this->contract->exp_date)) {
                            if (strtotime($contractExp) <= strtotime('+1 month')) {
                                $text = 'Sắp hết hạn';
                            }
                        }
                    }
                    break;
                case "het_han":
                    $text = 'Hết hạn';
                    break;
                case "da_thanh_ly":
                    $text = 'Đã thanh lý';
                    break;
                default:
                    $text = 'Nháp';
                    break;
            }
            return $text;
        }

        public function getAutoRenewedHtmlAttribute()
        {
            $html = '';
            switch ($this->auto_renewed) {
                case 1:
                    $html = '<span class="badge text-success badge-pill p-2"><i class="fas fa-check "></i></span>';
                    break;
                default:
                    $html = '<span class="badge text-danger badge-pill p-2"><i class="fas fa-times"></i></span>';
                    break;
            }

            return $html;
        }

        public function cacheKey()
        {
            return sprintf("%s/%s", $this->getTable(), $this->getKey());
        }
    }