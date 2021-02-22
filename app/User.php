<?php

    namespace App;

    use Illuminate\Foundation\Auth\User as Authenticatable;
    use Illuminate\Notifications\Notifiable;
    use Illuminate\Support\Arr;
    use Spatie\Permission\Models\Permission;
    use Spatie\Permission\Traits\HasRoles;

    class User extends Authenticatable
    {
        use Notifiable;
        use HasRoles;

        /**
         * The attributes that are mass assignable.
         *
         * @var array
         */
        protected $fillable = [
            'name', 'email', 'password', 'api_token','status'
        ];

        /**
         * The attributes that should be hidden for arrays.
         *
         * @var array
         */
        protected $hidden = [
            'password', 'remember_token',
        ];

        /**
         * The attributes that should be cast to native types.
         *
         * @var array
         */
        protected $casts   = [
            'email_verified_at' => 'datetime',
        ];
        protected $appends = ['role_html', 'permission_html', 'role'];

        public function getRoleAttribute()
        {
            $role = $this->getRoleNames();
            $role = Arr::first($role);
            if (!empty($role)) {
                return $role;
            }
            return null;
        }

        public function getRoleHtmlAttribute()
        {
            $role = $this->getRoleNames();
            $role = Arr::first($role);
            if (!empty($role)) {
                return '<span class="badge p-2 text-white badge-pill badge-info">'.$role.'</span>';
            }
            return null;
        }

        public function getPermissionHtmlAttribute()
        {
            $role = $this->getRoleNames();
            $role = Arr::first($role);
            if (!empty($role)) {
                return '<span class="badge p-2 badge-info">'.$role.'</span>';
            }
            return null;
        }

        public function search($request)
        {
            $filters = $request->queryParams['filters'] ?? [];
            $sorts = $request->queryParams['sort'] ?? [];
            $query = self::newQuery();
            if (!empty($filters)) {
                foreach ($filters as $filter) {
                    if ($filter['name'] == 'status_html') {
                        $query->whereIn('status', $filter['selected_options']);
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
            $query->orderBy('id', 'desc');
            return $query;
        }
        public function getAllPermissionsAttribute() {
            $permissions = [];
            foreach (Permission::all() as $permission) {
                if ($this->can($permission->name)) {
                    $permissions[] = $permission->name;
                }
            }
            return $permissions;
        }

    }
