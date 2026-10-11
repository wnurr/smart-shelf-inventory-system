<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [

            /*
            |--------------------------------------------------------------------------
            | Item
            |--------------------------------------------------------------------------
            */

            [
                'name' => 'view_items',
                'label' => 'View Items',
                'group' => 'Items',
            ],

            [
                'name' => 'manage_items',
                'label' => 'Manage Items',
                'group' => 'Items',
            ],


            /*
            |--------------------------------------------------------------------------
            | Stock
            |--------------------------------------------------------------------------
            */

            [
                'name' => 'view_stock',
                'label' => 'View Stock',
                'group' => 'Stock',
            ],


            /*
            |--------------------------------------------------------------------------
            | Sensor
            |--------------------------------------------------------------------------
            */

            [
                'name' => 'manage_sensors',
                'label' => 'Manage Sensors',
                'group' => 'Sensors',
            ],

            [
                'name' => 'view_sensors',
                'label' => 'View Sensors',
                'group' => 'Sensors',
            ],


            /*
            |--------------------------------------------------------------------------
            | Location
            |--------------------------------------------------------------------------
            */

            [
                'name' => 'manage_locations',
                'label' => 'Manage Locations',
                'group' => 'Locations',
            ],

            [
                'name' => 'view_locations',
                'label' => 'View Locations',
                'group' => 'Locations',
            ],


            /*
            |--------------------------------------------------------------------------
            | Calibration
            |--------------------------------------------------------------------------
            */

            [
                'name' => 'calibrate_sensor',
                'label' => 'Perform Calibration',
                'group' => 'Calibration',
            ],


            /*
            |--------------------------------------------------------------------------
            | History
            |--------------------------------------------------------------------------
            */

            [
                'name' => 'view_history',
                'label' => 'View History',
                'group' => 'History',
            ],


            /*
            |--------------------------------------------------------------------------
            | Reports
            |--------------------------------------------------------------------------
            */

            [
                'name' => 'generate_reports',
                'label' => 'Generate Reports',
                'group' => 'Reports',
            ],


            /*
            |--------------------------------------------------------------------------
            | Staff
            |--------------------------------------------------------------------------
            */

            [
                'name' => 'manage_staff',
                'label' => 'Manage Staff',
                'group' => 'Administration',
            ],


            /*
            |--------------------------------------------------------------------------
            | Roles
            |--------------------------------------------------------------------------
            */

            [
                'name' => 'manage_roles',
                'label' => 'Manage Roles',
                'group' => 'Administration',
            ],
        ];


        foreach ($permissions as $permission) {

            Permission::updateOrCreate(
                [
                    'name' =>
                        $permission['name'],
                ],
                $permission
            );
        }
    }
}