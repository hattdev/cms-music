<?php
namespace Modules\User\Controllers;

use Modules\ModuleController;
use Spatie\Permission\Models\Permission;

class PermissionController extends ModuleController
{
    /**
     * @var Permission
     */
    private $permission;

    public function __construct(Permission $permission)
    {
        parent::__construct();
        $this->permission = $permission;
    }
    public function all()
    {
        $rows = $this->permission->pluck('name');
        if (!empty($rows)) {
            return $this->sendSuccess(['data' => $rows]);
        }
        return $this->sendError('Không có dữ liệu');
    }

}