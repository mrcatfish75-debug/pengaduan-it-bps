<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ActivityLog;

class AdminSecurityLog
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->role === 'admin') {

            ActivityLog::create([
                'user_id'   => auth()->id(),
                'aksi'      => 'AKSES_ADMIN_PANEL',
                'model'     => 'Admin',
                'model_id'  => auth()->id(),
                'deskripsi' => 'Admin mengakses panel: '.$request->path(),
                'ip_address'=> $request->ip(),
                'user_agent'=> $request->userAgent(),
            ]);
        }

        return $next($request);
    }
}