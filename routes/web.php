<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\CalibrationController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Api\ReadingController;

use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;



/*
|--------------------------------------------------------------------------
| Public Landing Page
|--------------------------------------------------------------------------
*/

Route::get('/', function () {

    // If user is already logged in,
    // do not allow them to return to landing/login selection page.
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    $adminExists = User::whereHas('role', function ($query) {
        $query->where('position', 'admin');
    })->exists();

    return view(
        'welcome',
        compact('adminExists')
    );

})->middleware('prevent.back.history')->name('landing');


/*
|--------------------------------------------------------------------------
| Authenticated Users
|--------------------------------------------------------------------------
|
| All users must be logged in.
|
| Staff with temporary passwords must change them before accessing
| the Smart Shelf system.
|
*/

Route::middleware([
    'auth',
    'force.password.change',
    'prevent.back.history',
])->group(function () {


    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    |
    | All authenticated users can access Dashboard.
    |
    */

    Route::get(
        '/dashboard',
        [
            DashboardController::class,
            'index',
        ]
    )->name('dashboard');


    Route::get(
        '/dashboard/stats',
        [
            DashboardController::class,
            'stats',
        ]
    )->name('dashboard.stats');



    /*
    |--------------------------------------------------------------------------
    | Item - Create
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    |
    | /items/create must be declared before /items/{item}
    |
    */

    Route::middleware(
        'permission:manage_items'
    )->group(function () {

        Route::get(
            '/items/create',
            [
                ItemController::class,
                'create',
            ]
        )->name('items.create');
    });



    /*
    |--------------------------------------------------------------------------
    | Item - View
    |--------------------------------------------------------------------------
    |
    | Roles with view_items permission can view Item List
    | and Item Details.
    |
    */

    Route::middleware(
        'permission:view_items'
    )->group(function () {

        Route::get(
            '/items',
            [
                ItemController::class,
                'index',
            ]
        )->name('items.index');


        Route::get(
            '/items/{item}',
            [
                ItemController::class,
                'show',
            ]
        )->name('items.show');
    });



    /*
    |--------------------------------------------------------------------------
    | Item - Manage
    |--------------------------------------------------------------------------
    |
    | Roles with manage_items permission can:
    |
    | - Add Item
    | - Edit Item
    | - Delete Item
    |
    */

    Route::middleware(
        'permission:manage_items'
    )->group(function () {

        Route::post(
            '/items',
            [
                ItemController::class,
                'store',
            ]
        )->name('items.store');


        Route::get(
            '/items/{item}/edit',
            [
                ItemController::class,
                'edit',
            ]
        )->name('items.edit');


        Route::put(
            '/items/{item}',
            [
                ItemController::class,
                'update',
            ]
        )->name('items.update');


        Route::patch(
            '/items/{item}',
            [
                ItemController::class,
                'update',
            ]
        );


        Route::delete(
            '/items/{item}',
            [
                ItemController::class,
                'destroy',
            ]
        )->name('items.destroy');
    });



    /*
    |--------------------------------------------------------------------------
    | Stock
    |--------------------------------------------------------------------------
    |
    | Roles with view_stock permission can view current stock.
    |
    */

    Route::middleware(
        'permission:view_stock'
    )->group(function () {

        Route::get(
            '/stock',
            [
                StockController::class,
                'index',
            ]
        )->name('stock.index');
    });



    /*
    |--------------------------------------------------------------------------
    | History
    |--------------------------------------------------------------------------
    |
    | Roles with view_history permission can view inventory history.
    |
    */

    Route::middleware(
        'permission:view_history'
    )->group(function () {

        Route::get(
            '/history',
            [
                HistoryController::class,
                'index',
            ]
        )->name('history.index');
    });



    /*
|--------------------------------------------------------------------------
| Sensor - View
|--------------------------------------------------------------------------
*/

    Route::middleware(
        'permission:view_sensors'
    )->group(function () {

        Route::get(
            '/sensors',
            [
                SensorController::class,
                'index',
            ]
        )->name('sensors.index');
    });


    /*
|--------------------------------------------------------------------------
| Sensor - Manage
|--------------------------------------------------------------------------
*/

    Route::middleware(
        'permission:manage_sensors'
    )->group(function () {

        Route::get(
            '/sensors/create',
            [
                SensorController::class,
                'create',
            ]
        )->name('sensors.create');


        Route::post(
            '/sensors',
            [
                SensorController::class,
                'store',
            ]
        )->name('sensors.store');


        Route::delete(
            '/sensors/{sensor}',
            [
                SensorController::class,
                'destroy',
            ]
        )->name('sensors.destroy');
    });



 /*
|--------------------------------------------------------------------------
| Location - View
|--------------------------------------------------------------------------
*/

Route::middleware(
    'permission:view_locations'
)->group(function () {

    Route::get(
        '/locations',
        [
            LocationController::class,
            'index',
        ]
    )->name('locations.index');

});


/*
|--------------------------------------------------------------------------
| Location - Manage
|--------------------------------------------------------------------------
*/

Route::middleware(
    'permission:manage_locations'
)->group(function () {

    Route::get(
        '/locations/create',
        [
            LocationController::class,
            'create',
        ]
    )->name('locations.create');


    Route::post(
        '/locations',
        [
            LocationController::class,
            'store',
        ]
    )->name('locations.store');


    Route::get(
        '/locations/{location}/edit',
        [
            LocationController::class,
            'edit',
        ]
    )->name('locations.edit');


    Route::put(
        '/locations/{location}',
        [
            LocationController::class,
            'update',
        ]
    )->name('locations.update');


    Route::delete(
        '/locations/{location}',
        [
            LocationController::class,
            'destroy',
        ]
    )->name('locations.destroy');

});



    /*
    |--------------------------------------------------------------------------
    | Calibration
    |--------------------------------------------------------------------------
    |
    | Any role with calibrate_sensor permission can access and perform
    | calibration.
    |
    */

    Route::middleware(
        'permission:calibrate_sensor'
    )->group(function () {

        Route::get(
            '/calibration',
            [
                CalibrationController::class,
                'index',
            ]
        )->name('calibration.index');


        Route::get(
            '/calibration/commands/{command}',
            [
                CalibrationController::class,
                'commandStatus',
            ]
        )->name('calibration.command-status');


        /*
        |--------------------------------------------------------------------------
        | Live Reading
        |--------------------------------------------------------------------------
        |
        | This is a web route used by the Calibration page.
        |
        | It calls the API ReadingController internally.
        |
        */

        Route::get(
            '/calibration/live-reading',
            [
                ReadingController::class,
                'live',
            ]
        )->name('calibration.live-reading');


        Route::post(
            '/calibration/tare',
            [
                CalibrationController::class,
                'tare',
            ]
        )->name('calibration.tare');


        Route::post(
            '/calibration/scale',
            [
                CalibrationController::class,
                'calibrateScale',
            ]
        )->name('calibration.scale');


        Route::post(
            '/calibration/unit-weight',
            [
                CalibrationController::class,
                'setUnitWeight',
            ]
        )->name('calibration.unit-weight');

        Route::post(
    '/calibration/commands/{command}/expire',
    [CalibrationController::class, 'expireCommand']
)->name('calibration.commands.expire');

    });



/*
|--------------------------------------------------------------------------
| Stock Report
|--------------------------------------------------------------------------
*/

Route::get(
    '/reports/stock',
    [
        ReportController::class,
        'stock',
    ]
)->name('reports.stock');


Route::get(
    '/reports/stock/download',
    [
        ReportController::class,
        'downloadStock',
    ]
)->name('reports.stock.download');


/*
|--------------------------------------------------------------------------
| History Report
|--------------------------------------------------------------------------
*/

Route::get(
    '/reports/history',
    [
        ReportController::class,
        'history',
    ]
)->name('reports.history');


Route::get(
    '/reports/history/download',
    [
        ReportController::class,
        'downloadHistory',
    ]
)->name('reports.history.download');


    /*
    |--------------------------------------------------------------------------
    | Staff Management
    |--------------------------------------------------------------------------
    |
    | Roles with manage_staff permission can manage staff accounts.
    |
    | Admin automatically passes this permission because Admin has
    | permission bypass inside User::hasPermission().
    |
    */

    Route::middleware(
        'permission:manage_staff'
    )->group(function () {

        Route::get(
            '/staff',
            [
                StaffController::class,
                'index',
            ]
        )->name('staff.index');


        Route::get(
            '/staff/create',
            [
                StaffController::class,
                'create',
            ]
        )->name('staff.create');


        Route::post(
            '/staff',
            [
                StaffController::class,
                'store',
            ]
        )->name('staff.store');


        Route::get(
            '/staff/{staff}/edit',
            [
                StaffController::class,
                'edit',
            ]
        )->name('staff.edit');


        Route::put(
            '/staff/{staff}',
            [
                StaffController::class,
                'update',
            ]
        )->name('staff.update');


        Route::delete(
            '/staff/{staff}',
            [
                StaffController::class,
                'destroy',
            ]
        )->name('staff.destroy');
    });



    /*
    |--------------------------------------------------------------------------
    | Role Management
    |--------------------------------------------------------------------------
    |
    | Roles with manage_roles permission can:
    |
    | - View Roles
    | - Add Role
    | - Edit Permissions
    | - Delete Custom Roles
    |
    */

    Route::middleware(
        'permission:manage_roles'
    )->group(function () {

        Route::get(
            '/roles',
            [
                RoleController::class,
                'index',
            ]
        )->name('roles.index');


        Route::get(
            '/roles/create',
            [
                RoleController::class,
                'create',
            ]
        )->name('roles.create');


        Route::post(
            '/roles',
            [
                RoleController::class,
                'store',
            ]
        )->name('roles.store');


        Route::get(
            '/roles/{role}/edit',
            [
                RoleController::class,
                'edit',
            ]
        )->name('roles.edit');


        Route::put(
            '/roles/{role}',
            [
                RoleController::class,
                'update',
            ]
        )->name('roles.update');


        Route::delete(
            '/roles/{role}',
            [
                RoleController::class,
                'destroy',
            ]
        )->name('roles.destroy');
    });



    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    |
    | All authenticated users can access their profile.
    |
    */

    Route::get(
        '/profile',
        [
            ProfileController::class,
            'edit',
        ]
    )->name('profile.edit');


    Route::patch(
        '/profile',
        [
            ProfileController::class,
            'update',
        ]
    )->name('profile.update');


});


/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';
