<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckTeacher
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['teacher', 'hod'])) {
            abort(403, 'Teacher access required');
        }
        return $next($request);
    }
}
