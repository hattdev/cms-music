<?php
namespace Modules\User\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Modules\ModuleController;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends ModuleController
{
    protected $role_class;
    public function __construct(Role $role_class)
    {
        parent::__construct();
        $this->role_class = $role_class;
    }

    public function search(Request $request)
    {
        $per_page = $request->queryParams['per_page'] ?? LIMIT;
        $filters = $request->queryParams['filters'] ?? [];
        $sorts = $request->queryParams['sort'] ?? [];
        $query = new $this->role_class;
        if (!empty($filters)) {
            foreach ($filters as $filter) {

                if ($filter['name'] == 'name') {
                    $query->where('name', 'like', '%'.$filter['text'].'%');
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
        $rows = $query->paginate($per_page);
        $rows->map(function($value){
            $permissions = $value->getPermissionNames();
            foreach ($permissions as $permission) {
                $value->permission_html .= '<span class="badge text-white   badge-pill badge-info mr-2">'.$permission.'</span>';
            }
        });

        $data['rows'] = $rows->items();
        $columns = [
            'id'                         => "id",
            'name'              => 'Tên',
            'permission_html'              => 'Permission',
            'action'                     => "Hành động",
        ];
        $filtering = [
            'name',
        ];
        foreach ($columns as $k => $column) {
            $dataColumn = [
                "label" => $column,
                "name"  => $k,
            ];

            if ($k == 'id') {
                $dataColumn['sort'] = true;
                $dataColumn['uniqueId'] = true;
            }

            if (in_array($k, $filtering)) {
                $dataColumn['filter'] = ["type" => "simple", "case_sensitive" => true, 'showClearButton' => false, 'debounceRate' => 300];
            }
            if (in_array($k, ['sign_date', 'exp_date'])) {
                $dataColumn["sort"] = true;
            }
            if (in_array($k, ['status_html'])) {
                $dataColumn['slot_name'] = $k;
            }
            $data['columns'][] = $dataColumn;
        }
        return response()->json(['status' => 1, 'data' => $data]);
    }


    public function create(Request $request)
    {
        if (!empty($request->input())) {

        } else {
            $row = new User();
            $row->fill([
                'status' => 'publish'
            ]);
        }
        $data = [
            'row' => $row
        ];
        return view('User::role.detail', $data);
    }

    public function edit(Request $request, $id)
    {
        $row = $this->role_class->find($id);
        if (!empty($row)) {
            $data['data'] = $row;
            $data['permissions']  = $row->getPermissionNames();
            $data['status'] = 1;
            return response()->json($data);
        } else {
            $data['status'] = 0;
            $data['message'] = 'Có lỗi xẩy ra. Vui lòng thử lại';
            return response()->json($data);
        }
    }

    public function store(Request $request, $id=false){
        $row = $this->role_class;
        if ($id) {
            $row =$row->find($id);
        }
        try {
            $rules = [
                'name'            => 'required',
            ];
            $this->validate($request, $rules);
            if(empty($id)){
                $row->name = $request->name;
            }
            if ($row->save()) {
                if(!empty($request->permissions)){
                   $row->syncPermissions($request->permissions);
                }

            }
            return response()->json(['status' => 1, 'message' => 'Thành công']);
        } catch (\Exception $exception) {
            return response()->json(['status' => 0, 'message' => $exception->getMessage()]);
        }
    }



	public function permission_matrix()
    {
        $permissions = Permission::all();
        $permissions_group = [
            'other' => []
        ];
        if (!empty($permissions)) {
            foreach ($permissions as $permission) {
                $sCheck = strpos($permission->name, '_');
                if ($sCheck == false) {
                    $permissions_group['other'][] = $permission;
                    continue;
                }
                $grName = substr($permission->name, 0, $sCheck);
                if (!isset($permissions_group[$grName]))
                    $permissions_group[$grName] = [];
                $permissions_group[$grName][] = $permission;
            }
        }
        if (empty($permissions_group['other'])) {
            unset($permissions_group['other']);
        }
        $roles = Role::all();
        $selectedIds = [];
        if (!empty($roles)) {
            foreach ($roles as $role) {
                $selectedIds[$role->id] = [];
                $selected = $role->permissions;
                if (!empty($selected)) {
                    foreach ($selected as $permission) {
                        $selectedIds[$role->id][] = $permission->id;
                    }
                }
            }
        }
        $data = [
            'permissions'       => $permissions,
            'roles'             => $roles,
            'permissions_group' => $permissions_group,
            'selectedIds'       => $selectedIds,
            'role'              => $role
        ];
        return view('User::role.permission_matrix', $data);
    }

    public function save_permissions(Request $request)
    {
        $matrix = $request->input('matrix');
        $matrix = is_array($matrix) ? $matrix : [];
        if (!empty($matrix)) {
            foreach ($matrix as $role_id => $permissionIds) {
                $role = Role::find($role_id);
                if (!empty($role)) {
                    $permissions = Permission::find($permissionIds);
                    $role->syncPermissions($permissions);
                }
            }
        }
        return redirect()->back()->with('success', __('Permission Matrix updated'));
    }

    public function select2(Request $request)
    {
        $q = $request->search;
        $first = $request->first;
        if (!empty($q)) {
            $query = new Role();
            if (!$first) {
                $rows = $query->where('name', 'like', '%'.$q.'%')->get(['name as label', 'id']);
            } else {
                $rows = $query->where('name', $q)->select(['id', 'name as label'])->first();
            }
            if (!empty($rows)) {
                return $this->sendSuccess(['data' => $rows]);
            }
        }
        return $this->sendError('Không có dữ liệu');

    }
}
