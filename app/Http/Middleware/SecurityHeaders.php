<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Basic Security Headers
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // 🔐 CSP ONLY IN PRODUCTION
        if (app()->environment('production')) {

            $response->headers->set(
                'Content-Security-Policy',
                "default-src 'self';
                 script-src 'self';
                 style-src 'self';
                 img-src 'self' data:;
                 font-src 'self';"
            );
        }

        return $response;
    }
}