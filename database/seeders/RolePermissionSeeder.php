<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Store Manager
        |--------------------------------------------------------------------------
        */

        $manager = Role::where(
            'position',
            'store_manager'
        )->first();

        if ($manager) {

            $managerPermissions = Permission::whereIn(
                'name',
                [
                    'view_items',
                    'manage_items',
                    'view_stock',
                    'view_sensors',
                    'manage_sensors',
                    'view_locations',
                    'manage_locations',
                    'calibrate_sensor',
                    'view_history',
                    'generate_reports',
                ]
            )->pluck('id');

            $manager->permissions()
                ->sync($managerPermissions);
        }


        /*
        |--------------------------------------------------------------------------
        | Store Keeper
        |--------------------------------------------------------------------------
        */

        $keeper = Role::where(
            'position',
            'store_keeper'
        )->first();

        if ($keeper) {

            $keeperPermissions = Permission::whereIn(
                'name',
                [
                    'view_items',
                    'view_stock',
                    'view_history',
                    'view_sensors',
                    'view_locations',
                ]
            )->pluck('id');

            $keeper->permissions()
                ->sync($keeperPermissions);
        }
    }
}