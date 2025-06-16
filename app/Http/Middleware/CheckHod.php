<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckHod
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->isHod()) {
            abort(403, 'Hod access required');
        }
        return $next($request);
    }
}