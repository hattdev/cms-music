<?php


    namespace Modules\Media\Models;


    use Illuminate\Database\Eloquent\SoftDeletes;
    use Modules\BaseModel;

    class MediaRelation extends BaseModel
    {
        use SoftDeletes;
        protected $table    = 'file_uploads_relations';
        protected $fillable = [
            'id',
            'object_id',
            'object_model',
            'file_id',
            'create_user',
            'update_user',
        ];
    }