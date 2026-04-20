<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * Global HTTP middleware stack.
     */
    protected $middleware = [
        \Illuminate\Http\Middleware\HandleCors::class, // ✅ ADD THIS
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * Route middleware aliases.
     */
    protected $middlewareAliases = [
        'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
    ];

    /**
     * Route middleware (backward compatibility).
     */
    protected $routeMiddleware = [
        'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
    ];
}