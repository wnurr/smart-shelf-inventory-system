<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ForcePasswordController extends Controller
{
    /**
     * Show forced password change form.
     */
    public function edit(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | Already changed password
        |--------------------------------------------------------------------------
        |
        | If staff already changed their temporary password,
        | they should not be able to access this page again.
        |
        */

        if (! $user->must_change_password) {
            return redirect()->route('dashboard');
        }

        return view('auth.force-password-change');
    }


    /**
     * Update temporary password.
     */
    public function update(Request $request): RedirectResponse
    {
        /*Validate password*/

        $validated = $request->validate([
            'current_password' => [
                'required',
                'current_password',
            ],

            'password' => [
                'required',
                Password::defaults(),
                'confirmed',
            ],
        ]);


        /*New password cannot equal temporary password*/

        if (
            Hash::check(
                $validated['password'],
                $request->user()->password
            )
        ) {

            throw ValidationException::withMessages([
                'password' =>
                    'New password must be different from the temporary password.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Save new password
        |--------------------------------------------------------------------------
        */

        $request->user()->forceFill([
            'password' => Hash::make(
                $validated['password']
            ),

            'must_change_password' => false,
        ])->save();

        $request->session()->regenerate();


        /*
        |--------------------------------------------------------------------------
        | Redirect into system
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('dashboard')
            ->with(
                'success',
                'Password changed successfully. You can now use the system.'
            );
    }
}