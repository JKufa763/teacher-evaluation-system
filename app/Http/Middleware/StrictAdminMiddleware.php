<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class StrictAdminMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            $response = $next($request);

            // Add cache-control headers to prevent caching
            return $response->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
                           ->header('Pragma', 'no-cache')
                           ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
        }

        return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
    }
}