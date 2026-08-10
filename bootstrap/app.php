<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Daftarkan alias middleware custom
        $middleware->alias([
            'auth.admin' => \App\Http\Middleware\AdminAuth::class,
        ]);

        // Laravel 11: tidak ada Http/Kernel.php lagi.
        // Semua middleware global & grup dikonfigurasi di sini.
        // Grup 'web' sudah otomatis include: EncryptCookies, AddQueuedCookies,
        // StartSession, ShareErrorsFromSession, VerifyCsrfToken, SubstituteBindings.
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Tampilkan halaman 404 yang lebih baik
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Resource tidak ditemukan.'], 404);
            }
        });
    })
    ->create();
