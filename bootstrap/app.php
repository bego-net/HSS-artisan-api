<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register the CORS middleware at the top of the global stack.
        // prepend() adds it WITHOUT replacing Laravel's default middleware
        // (which includes things like EncryptCookies, StartSession, etc.)
        $middleware->prepend(\Illuminate\Http\Middleware\HandleCors::class);

        // NOTE: Do NOT use $middleware->statefulApi() here.
        // That applies the 'web' middleware group (including CSRF) to API routes
        // from stateful domains, causing 419 errors. We use pure token auth instead.

        // Register route-level middleware aliases
        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();