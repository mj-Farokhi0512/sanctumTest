<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Exceptions\RoleDoesNotExist;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // $userRole = Role::where('name', 'USER')->first();
        $users = \App\Models\User::factory(10)->create();

        // $user = User::create([
        //     'username' => 'Manager',
        //     'email' => 'manager@gmail.com',
        //     'password' => Hash::make('manager1234')
        // ]);

        // $role = Role::where('name', 'MANAGER')->first();
        // $user->assignRole($role);
    }
}
