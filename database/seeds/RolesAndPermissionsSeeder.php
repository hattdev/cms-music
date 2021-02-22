<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Roles
        Permission::findOrCreate('contract_manager');
        Permission::findOrCreate('content_manager');
        Permission::findOrCreate('partner_manager');
        Permission::findOrCreate('invoice_manager');
        Permission::findOrCreate('permission_manager');

        Permission::findOrCreate('dashboard_access');

        $customer = Role::findOrCreate('customer');

        $role = Role::findOrCreate('administrator');

        $role->givePermissionTo(Permission::all());
    }

}
