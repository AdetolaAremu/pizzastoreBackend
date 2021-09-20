<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = Permission::all();

        $super_admin = Role::whereName('Super Admin')->first();

        foreach ($permissions as $permission) {
            DB::table('role_permission')->insert([
                'role_id' => $super_admin->id,
                'permission_id' => $permission->id
            ]);
        }

        $admin = Role::whereName('Admin')->first();

        foreach ($permissions as $permission) {
            if (!in_array($permission->name, ['edit_roles', 'delete_users'])) {
                DB::table('role_permission')->insert([
                    'role_id' => $admin->id,
                    'permission_id' => $permission->id
                ]);
            }
        }

        $viewer = Role::whereName('User')->first();

        $userRole = [
            // 'view_roles',
            // 'view_users',
            'view_pizza',
            'view_cart',
            'edit_cart',
            'view_review',
        ];

        foreach ($permissions as $permission) {
            if (in_array($permission->name, $userRole)) {
                DB::table('role_permission')->insert([
                    'role_id' => $viewer->id,
                    'permission_id' => $permission->id
                ]);
            }
        }
    }
}
