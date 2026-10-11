<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AdminLoginController extends Controller
{
    /**
     * Show Admin login page.
     */
    public function create(): View
    {
        $adminExists = User::whereHas('role', function ($query) {
            $query->where('position', 'admin');
        })->exists();

        return view('auth.admin-login', compact('adminExists'));
    }

    /**
     * Handle Admin login.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Use Laravel Breeze authentication
        $request->authenticate();

        $user = $request->user();

        // Credentials may be correct,
        // but only an Admin can use this login page.
        if (! $user->hasRole('admin')) {

            Auth::guard('web')->logout();

            throw ValidationException::withMessages([
                'email' => 'This account is not registered as an administrator.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(
            route('dashboard', absolute: false)
        );
    }
}