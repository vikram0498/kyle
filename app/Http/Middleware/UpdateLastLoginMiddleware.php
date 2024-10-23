<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class UpdateLastLoginMiddleware
{
    public function handle($request, Closure $next)
    {
        // Check if the user is authenticated
        if (Auth::check()) {
            // Update the last_login_at column for the authenticated user
            Auth::user()->update(['login_at' => now()]);
        }

        return $next($request);
    }
}
