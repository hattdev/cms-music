<?php


    namespace Modules\Media\Models;


    use Illuminate\Database\Eloquent\SoftDeletes;
    use Illuminate\Support\Str;
    use Modules\BaseModel;
    use Modules\Media\Helpers\FileHelper;

    class Media extends BaseModel
    {
        use SoftDeletes;
        protected $table    = 'file_uploads';
        protected $appends  = ['url', 'thumb_size', 'full_size', 'medium_size'];
        protected $fillable = [
            'id',
            'file_name',
            'file_path',
            'file_mime_type',
            'file_extension',
            'file_size',
        ];

        public function getUrlAttribute()
        {
            return url('/uploads/'.$this->file_path);
        }

        public function getThumbSizeAttribute()
        {
            if (env('APP_PREVIEW_MEDIA_LINK')) {
                return route('media.preview', ['id' => $this->id, 'size' => 'thumb']);
            } else {
                return get_file_url($this, 'thumb');
            }
        }

        public function getFullSizeAttribute()
        {
            if (env('APP_PREVIEW_MEDIA_LINK')) {
                return route('media.preview', ['id' => $this->id, 'size' => 'full']);
            } else {
                return get_file_url($this, 'full');
            }
        }

        public function getMediumSizeAttribute()
        {
            if (env('APP_PREVIEW_MEDIA_LINK')) {
                return route('media.preview', ['id' => $this->id, 'size' => 'medium']);
            } else {
                return get_file_url($this, 'medium');
            }
        }

        public static function findMediaByName($name)
        {
            return self::where("file_name", $name)->firstOrFail();
        }

        public function cacheKey()
        {
            return sprintf("%s/%s", $this->getTable(), $this->getKey());
        }

        public static function importUploadFiles($row, $name = 'danh_sach_files')
        {
            if (!empty($row[$name])) {
                $danh_sach_files = $row[$name] ?? "";
                $danh_sach_files = Str::of($danh_sach_files)->explode(',');
                $fileIds = [];
                foreach ($danh_sach_files as $file) {
                    $path = Str::replaceFirst('uploads', '', $file);
                    $path = str_replace("\\", '/', $path);
                    $path = str_replace("//", '/', $path);
                    $full_path = public_path('uploads/'.$path);
                    if (!empty($file) and \Storage::disk('uploads')->exists($path)) {
                        $checkFile = Media::where('file_path', $full_path)->first();
                        if (!empty($checkFile)) {
                            $fileIds[] = $checkFile->id;
                        } else {
                            $file_MimeType = \File::mimeType($full_path);
                            $file_size = \File::size($full_path);
                            $file_name = \File::name($full_path);
                            $file_extension = \File::extension($full_path);

                            $fileObj = new Media();
                            $fileObj->file_name = $file_name;
                            $fileObj->file_path = $path;
                            $fileObj->file_size = $file_size;
                            $fileObj->file_type = $file_MimeType;
                            $fileObj->file_extension = $file_extension;
                            if (FileHelper::checkMimeIsImage($file_extension)) {
                                list($width, $height, $type, $attr) = getimagesize($full_path);
                                $fileObj->file_width = $width;
                                $fileObj->file_height = $height;
                            }
                            $fileObj->save();
                            $fileIds[] = $fileObj->id;
                        }

                    }else{
                        throw new \Exception(__("Không tìm thấy file: ".$file));
                    }

                }
                if (!empty($fileIds)) {
                    return implode(',', $fileIds);
                }
            }
            return null;
        }

    }