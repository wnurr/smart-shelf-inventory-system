<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Role::firstOrCreate([
            'position' => 'admin',
        ]);

        Role::firstOrCreate([
            'position' => 'store_manager',
        ]);

        Role::firstOrCreate([
            'position' => 'store_keeper',
        ]);

        User::firstOrCreate(
            ['email' => 'admin@smartshelf.com'],
            [
                'name' => 'System Admin',
                'phone_no' => null,
                'role_id' => $admin->id,
                'password' => Hash::make('admin123'),
            ]
        );

        $this->call([
            PermissionSeeder::class,
            RolePermissionSeeder::class,
        ]);
    }
}
