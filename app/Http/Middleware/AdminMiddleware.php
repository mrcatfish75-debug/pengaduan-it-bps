<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
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

        if ($user->role !== 'admin') {

            // Optional: logout biar session tidak nyangkut
            Auth::logout();

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Forbidden - Admin Only'
                ], 403);
            }

            return redirect()->route('login')
                ->with('error', 'Akses khusus Admin');
        }

        return $next($request);
    }
}