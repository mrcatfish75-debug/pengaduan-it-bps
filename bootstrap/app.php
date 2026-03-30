<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))

    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware) {

        // ================= REGISTER MIDDLEWARE ROLE =================
        $middleware->alias([
            'admin'   => \App\Http\Middleware\AdminMiddleware::class,
            'pegawai' => \App\Http\Middleware\PegawaiMiddleware::class,
            'kasubag' => \App\Http\Middleware\KasubagMiddleware::class,
        ]);

    })

    ->withExceptions(function (Exceptions $exceptions) {

        // ================= AUTH EXCEPTION =================
        $exceptions->render(function (AuthenticationException $e, $request) {
            return redirect()->route('login');
        });

        // ================= HANDLE 403 =================
        $exceptions->render(function (HttpException $e, $request) {

            if ($e->getStatusCode() === 403) {
                return redirect()->route('login')
                    ->with('error', 'Akses tidak diizinkan');
            }

        });

        // ================= HANDLE 419 (SESSION EXPIRED) =================
        $exceptions->render(function (HttpException $e, $request) {

            if ($e->getStatusCode() === 419) {
                return redirect()->route('login')
                    ->with('error', 'Session habis, silakan login ulang.');
            }

        });

    })

    ->create();