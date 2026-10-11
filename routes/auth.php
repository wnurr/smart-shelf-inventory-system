<?php

use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\ForcePasswordController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\StaffLoginController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest', 'prevent.back.history')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Admin Login
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/admin/login',
        [
            AdminLoginController::class,
            'create',
        ]
    )->name('admin.login');


    Route::post(
        '/admin/login',
        [
            AdminLoginController::class,
            'store',
        ]
    )->name('admin.login.store');


    /*
    |--------------------------------------------------------------------------
    | Staff Login
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/staff/login',
        [
            StaffLoginController::class,
            'create',
        ]
    )->name('staff.login');


    Route::post(
        '/staff/login',
        [
            StaffLoginController::class,
            'store',
        ]
    )->name('staff.login.store');


    /*
    |--------------------------------------------------------------------------
    | Forgot Password
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/forgot-password',
        [
            PasswordResetLinkController::class,
            'create',
        ]
    )->name('password.request');


    Route::post(
        '/forgot-password',
        [
            PasswordResetLinkController::class,
            'store',
        ]
    )->name('password.email');


    Route::get(
        '/reset-password/{token}',
        [
            NewPasswordController::class,
            'create',
        ]
    )->name('password.reset');


    Route::post(
        '/reset-password',
        [
            NewPasswordController::class,
            'store',
        ]
    )->name('password.store');
});


/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Forced Staff Password Change
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    |
    | These routes should NOT use force.password.change middleware.
    |
    | Otherwise the middleware would redirect the user back
    | to the same page forever.
    |
    */

    Route::get(
        '/force-password-change',
        [
            ForcePasswordController::class,
            'edit',
        ]
    )->name('password.force.edit');


    Route::put(
        '/force-password-change',
        [
            ForcePasswordController::class,
            'update',
        ]
    )->name('password.force.update');


    /*
    |--------------------------------------------------------------------------
    | Email Verification
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/verify-email',
        EmailVerificationPromptController::class
    )->name('verification.notice');


    Route::get(
        '/verify-email/{id}/{hash}',
        VerifyEmailController::class
    )
        ->middleware([
            'signed',
            'throttle:6,1',
        ])
        ->name('verification.verify');


    Route::post(
        '/email/verification-notification',
        [
            EmailVerificationNotificationController::class,
            'store',
        ]
    )
        ->middleware('throttle:6,1')
        ->name('verification.send');


    /*
    |--------------------------------------------------------------------------
    | Confirm Password
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/confirm-password',
        [
            ConfirmablePasswordController::class,
            'show',
        ]
    )->name('password.confirm');


    Route::post(
        '/confirm-password',
        [
            ConfirmablePasswordController::class,
            'store',
        ]
    );


    /*
    |--------------------------------------------------------------------------
    | Normal Profile Password Change
    |--------------------------------------------------------------------------
    */

    Route::put(
        '/password',
        [
            PasswordController::class,
            'update',
        ]
    )->name('password.update');


    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/logout',
        [
            AuthenticatedSessionController::class,
            'destroy',
        ]
    )->name('logout');
});