<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $user = Role::create(['name' => 'USER', 'guard_name' => 'api']);
        $manager = Role::create(['name' => 'MANAGER', 'guard_name' => 'api']);
        $admin = Role::create(['name' => 'ADMIN', 'guard_name' => 'api']);


        $permissions = [['name' => 'read_user', 'guard_name' => 'api'], ['name' => 'update_user', 'guard_name' => 'api'], ['name' => 'delete_user', 'guard_name' => 'api'], ['name' => 'create_user', 'guard_name' => 'api'], ['name' => 'create_role', 'guard_name' => 'api'], ['name' => 'delete_role', 'guard_name' => 'api'], ['name' => 'update_role', 'guard_name' => 'api'], ['name' => 'read_role', 'guard_name' => 'api'], ['name' => 'read_category', 'guard_name' => 'api'], ['name' => 'update_category', 'guard_name' => 'api'], ['name' => 'delete_category', 'guard_name' => 'api'], ['name' => 'create_category', 'guard_name' => 'api'], ['name' => 'read_tag', 'guard_name' => 'api'], ['name' => 'create_tag', 'guard_name' => 'api'], ['name' => 'update_tag', 'guard_name' => 'api'], ['name' => 'delete_tag', 'guard_name' => 'api']];


        Permission::insert($permissions);


        $manager->syncPermissions(['read_user', 'update_user', 'delete_user', 'create_user', 'create_role', 'delete_role', 'update_role', 'read_role', 'read_category', 'update_category', 'delete_category', 'create_category', 'read_tag', 'create_tag', 'update_tag', 'delete_tag']);
        $admin->syncPermissions(['read_category', 'update_category', 'delete_category', 'create_category', 'read_tag', 'create_tag', 'update_tag', 'delete_tag']);
    }
}
