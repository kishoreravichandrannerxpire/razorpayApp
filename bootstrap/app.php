<?php

use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\RedirectIfAdmin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Render proxy pinnaadi irukku, adhunaala trust pannanum
        $middleware->trustProxies(at: '*');

        $middleware->validateCsrfTokens(except: [
            'razorpay/webhook',
        ]);

        // Register custom middleware aliases
        $middleware->alias([
            'admin' => EnsureUserIsAdmin::class,
            'redirect_if_admin' => RedirectIfAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();