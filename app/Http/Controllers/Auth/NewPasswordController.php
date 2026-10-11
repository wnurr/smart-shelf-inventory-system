<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view(
            'auth.reset-password',
            [
                'request' => $request,
            ]
        );
    }


    /**
     * Handle password reset.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => [
                'required',
            ],

            'email' => [
                'required',
                'email',
            ],

            'password' => [
                'required',
                'confirmed',
                Rules\Password::defaults(),
            ],
        ]);


        $status = Password::reset(

            $request->only(
                'email',
                'password',
                'password_confirmation',
                'token'
            ),

            function (User $user) use ($request) {

                $user->forceFill([
                    'password' => Hash::make(
                        $request->password
                    ),

                    'remember_token' => Str::random(60),

                ])->save();


                event(
                    new PasswordReset($user)
                );
            }
        );


        /*
        |--------------------------------------------------------------------------
        | Successful password reset
        |--------------------------------------------------------------------------
        |
        | Since we have separate Admin and Staff login pages,
        | send the user back to the landing page.
        |
        */

        return $status == Password::PASSWORD_RESET

            ? redirect()
                ->route('landing')
                ->with(
                    'status',
                    __($status)
                )

            : back()
                ->withInput(
                    $request->only('email')
                )
                ->withErrors([
                    'email' => __($status),
                ]);
    }
}