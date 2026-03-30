<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class PegawaiMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {

        /*
        |--------------------------------------------------------------------------
        | Check Authentication
        |--------------------------------------------------------------------------
        */

        if (!Auth::check()) {

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthenticated'
                ], 401);
            }

            return redirect()->route('login');
        }

        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Check Role (FIXED)
        |--------------------------------------------------------------------------
        */

        if ($user->role !== 'pegawai') {

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Forbidden - Pegawai Only'
                ], 403);
            }

            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}