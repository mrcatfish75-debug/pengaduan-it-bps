<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminIpWhitelist
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'admin') {

            $allowedIps = config('security.admin_whitelist_ips');

            if (!in_array($request->ip(), $allowedIps)) {

                Auth::logout();

                abort(403, 'Akses ditolak. IP Anda tidak diizinkan.');
            }
        }

        return $next($request);
    }
}