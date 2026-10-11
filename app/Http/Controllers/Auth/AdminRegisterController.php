<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class AdminRegisterController extends Controller
{
    /**
     * Check whether the system already has an Admin.
     */
    private function adminExists(): bool
    {
        return User::whereHas('role', function ($query) {
            $query->where('position', 'admin');
        })->exists();
    }


    /**
     * Show first Admin registration page.
     */
    public function create(): View|RedirectResponse
    {
        // Once an Admin exists, public Admin registration is closed.
        if ($this->adminExists()) {

            return redirect()
                ->route('admin.login')
                ->with(
                    'status',
                    'Administrator registration is no longer available.'
                );
        }

        return view('auth.admin-register');
    }


    /**
     * Create the first Admin.
     */
    public function store(Request $request): RedirectResponse
    {
        // Security check again on POST request.
        if ($this->adminExists()) {

            return redirect()
                ->route('admin.login')
                ->with(
                    'status',
                    'An Administrator account already exists.'
                );
        }


        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'phone_no' => [
                'nullable',
                'string',
                'max:20',
            ],

            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'confirmed',
                Rules\Password::defaults(),
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Find or create Admin role
        |--------------------------------------------------------------------------
        */

        $adminRole = Role::firstOrCreate([
            'position' => 'admin',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Create Admin
        |--------------------------------------------------------------------------
        */

        $user = User::create([
            'name' => $validated['name'],
            'phone_no' => $validated['phone_no'] ?? null,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $adminRole->id,
        ]);


        event(new Registered($user));


        /*
        |--------------------------------------------------------------------------
        | Automatically login first Admin
        |--------------------------------------------------------------------------
        */

        Auth::login($user);

        $request->session()->regenerate();


        return redirect()
            ->route('dashboard')
            ->with(
                'success',
                'Administrator account created successfully.'
            );
    }
}