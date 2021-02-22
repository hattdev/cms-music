<?php


namespace Modules;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ModuleController extends Controller
{
    public function __construct()
    {

    }
    public function sendError($message,$data = []){

        $data['status'] = 0;

        return $this->sendSuccess($data,$message);

    }

    public function sendSuccess($data = [],$message = '')
    {
        if(is_string($data))
        {
            return response()->json([
                'message'=>$data,
                'status'=>true
            ]);
        }

        if(!isset($data['status'])) $data['status'] = 1;

        if($message)
            $data['message'] = $message;

        return response()->json($data);
    }
    public function checkPermission($permission = false)
    {
        if ($permission) {
            if (!Auth::id() or !Auth::user()->hasPermissionTo($permission)) {
                abort(403);
            }
        }
    }

    public function hasPermission($permission)
    {
        return Auth::user()->hasPermissionTo($permission);
    }

}
