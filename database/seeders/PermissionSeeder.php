<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::insert([
            ['name' => 'view_roles'],
            ['name' => 'edit_roles'],
            ['name' => 'view_users'],
            ['name' => 'edit_users'],
            ['name' => 'delete_users'],
            ['name' => 'ban_users'],
            ['name' => 'create_pizza'],
            ['name' => 'view_pizza'],
            ['name' => 'edit_pizza'],
            ['name' => 'delete_pizza'],
            ['name' => 'create_pizza_variant'],
            ['name' => 'view_pizza_variant'],
            ['name' => 'edit_pizza_variant'],
            ['name' => 'delete_pizza_variant'],
            ['name' => 'view_cart'],
            ['name' => 'edit_cart'],
            ['name' => 'view_reviews'],
            ['name' => 'edit_reviews'],
            ['name' => 'delete_reviews'],
        ]);
    }
}
