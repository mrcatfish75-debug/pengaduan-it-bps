<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Log;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware): void {

        // ================================
        // ALIAS MIDDLEWARE
        // ================================
        $middleware->alias([
            'admin'     => \App\Http\Middleware\AdminMiddleware::class,
            'pegawai'   => \App\Http\Middleware\PegawaiMiddleware::class,
            'kasubag'   => \App\Http\Middleware\KasubagMiddleware::class,
            'admin.ip'  => \App\Http\Middleware\AdminIpWhitelist::class,
        ]);

        // ================================
        // GLOBAL SECURITY HEADERS
        // ================================
        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);

    })

    ->withExceptions(function (Exceptions $exceptions): void {

        /*
        |--------------------------------------------------------------------------
        | Production Error Logging (Safe Mode)
        |--------------------------------------------------------------------------
        */

        $exceptions->report(function (\Throwable $e) {

            if (app()->environment('production')) {

                Log::error('SYSTEM_ERROR', [
                    'message' => $e->getMessage(),
                    'file'    => $e->getFile(),
                    'line'    => $e->getLine(),
                    'ip'      => request()->ip(),
                    'url'     => request()->fullUrl(),
                    'method'  => request()->method(),
                ]);

            }

        });

    })

    ->create();