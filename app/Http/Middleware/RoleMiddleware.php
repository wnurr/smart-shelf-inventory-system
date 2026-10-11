<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(
        Request $request,
        Closure $next,
        ...$roles
    ): Response {

        /*
        |--------------------------------------------------------------------------
        | User must be logged in
        |--------------------------------------------------------------------------
        */

        if (! $request->user()) {

            return redirect()->route('landing');

        }


        /*
        |--------------------------------------------------------------------------
        | Check user's role
        |--------------------------------------------------------------------------
        */

        $userRole = $request->user()->role?->position;


        if (! in_array($userRole, $roles, true)) {

            abort(
                403,
                'You are not authorized to access this page.'
            );

        }


        return $next($request);
    }
}