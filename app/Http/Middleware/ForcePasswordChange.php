<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordChange
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | Not logged in
        |--------------------------------------------------------------------------
        */

        if (! $user) {
            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | Only apply this rule to staff
        |--------------------------------------------------------------------------
        |
        | Admin does not need forced password changing because Admin
        | creates their own password during registration.
        |
        */

        $isStaff = $user->role &&
        
        $user->role->position !== 'admin';

        /*
        |--------------------------------------------------------------------------
        | Force staff to password change page
        |--------------------------------------------------------------------------
        */

        if ($isStaff && $user->must_change_password) {
            return redirect()->route('password.force.edit');
        }

        return $next($request);
    }
}