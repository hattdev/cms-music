<?php


    namespace Modules\User\Controllers;


    use App\User;
    use Illuminate\Http\Request;
    use Illuminate\Support\Str;
    use Modules\ModuleController;
    use Spatie\Permission\Models\Role;

    class UserController extends ModuleController
    {

        /**
         * @var User
         */
        private $user;

        public function __construct(User $user)
        {
            parent::__construct();
            $this->user = $user;
        }

        public function search(Request $request)
        {
            $per_page = $request->queryParams['per_page'] ?? LIMIT;
            $query = $this->user->search($request);
            $rows = $query->paginate($per_page);

            $data['rows'] = $rows->items();
            $columns = [
                'id'                         => "id",
                'name'              => 'Tên',
                'email'              => 'Email',
                'role_html'              => 'Role',
                'status_html'                => "Trạng thái",
                'action'                     => "Hành động",
            ];
            $filtering = [
                'name',
                'email',
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
                    if ($k == 'status_html') {
                        $dataColumn['filter'] = [
                            'type'        => 'select',
                            'mode'        => 'single',
                            'placeholder' => 'Chọn Trạng thái',
                            'options'     => [
                                [
                                    'name'  => USER_STATUS[NHAP],
                                    'value' => NHAP,
                                ],
                                [
                                    'name'  => USER_STATUS[STATUS_PUBLIC],
                                    'value' => STATUS_PUBLIC,
                                ],
                                [
                                    'name'  => USER_STATUS[STATUS_BLOCK],
                                    'value' => STATUS_BLOCK,
                                ],
                            ],

                        ];
                    }
                }
                $data['columns'][] = $dataColumn;
            }
            return response()->json(['status' => 1, 'data' => $data]);
        }

        public function edit(Request $request, $id)
        {

            $row = $this->user->find($id);
            if (!empty($row)) {
                $data['data'] = $row;
                $data['status'] = 1;
                return response()->json($data);
            } else {
                $data['status'] = 0;
                $data['message'] = 'Có lỗi xẩy ra. Vui lòng thử lại';
                return response()->json($data);
            }
        }

        public function store(Request $request, $id = null)
        {
            $inputAll = $request->all();
            $row = $this->user;
            if ($id) {
                $row =$row->find($id);
            }
            try {
                $rules = [
                    'email'    => 'required',
                    'name'            => 'required',
                    'status'          => 'required',
                    'create_user',
                    'update_user',
                ];
                if(empty($id)){
                    $rules['password'] ='required';
                }
                $this->validate($request, $rules);
                $row->fill($inputAll);
                if(!empty($request->password)){
                    $row->password = bcrypt($request->password);
                }
                $row->api_token = Str::random(60);

                if ($row->save()) {
                    if(!empty($request->role)){
                        $role = Role::where('name',$request->role)->first();
                        if(!empty($role)){
                            $row->assignRole($request->role);
                        }
                    }

                }
                return response()->json(['status' => 1, 'message' => 'Thành công']);
            } catch (\Exception $exception) {
                return response()->json(['status' => 0, 'message' => $exception->getMessage()]);
            }


        }

        public function delete(Request $request)
        {
            $ids = $request->ids;
            if (!empty($contentType) and !empty($ids) and is_array($ids)) {
                $query = $this->user;
                $query->whereIn('id', $request->ids)->delete();
                return $this->sendSuccess([], 'Xóa thành công');
            }
            return $this->sendError('Không tìm thấy thông tin. Vui lòng thử lại');
        }
    }