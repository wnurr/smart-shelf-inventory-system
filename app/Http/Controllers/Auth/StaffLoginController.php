<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class StaffLoginController extends Controller
{
    /**
     * Show Staff login page.
     */
    public function create(): View
    {
        return view('auth.staff-login');
    }


    /**
     * Handle Staff login.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        /*Authenticate email and password*/

        $request->authenticate();

        $user = $request->user();


        /*Only Staff can use Staff Login*/


        if (
    ! $user->role ||
    $user->role->position === 'admin'
) {
    Auth::guard('web')->logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    throw ValidationException::withMessages([
        'email' =>
            'This email is not registered as a staff account.',
    ]);
}


        /*egenerate session*/

        $request->session()->regenerate();


        /*Force temporary password change*/

        if ($user->must_change_password) {

            return redirect()->route(
                'password.force.edit'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Normal login
        |--------------------------------------------------------------------------
        */

        return redirect()->intended(
            route('dashboard', absolute: false)
        );
    }
}