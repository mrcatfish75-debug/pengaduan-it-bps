<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class KasubagMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user()->role !== 'kasubag') {
            abort(403, 'Akses hanya untuk Kasubag');
        }

        return $next($request);
    }
}