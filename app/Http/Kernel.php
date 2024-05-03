<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{

    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \App\Http\Middleware\JsonMiddleware::class,
        \App\Http\Middleware\TrimStrings::class,
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [        
        'auth' => \App\Http\Middleware\Authenticate::class,
        'cors' => \App\Http\Middleware\Cors::class,
    ];

    protected $middlewareGroups = [
        'api' => [
            \App\Http\Middleware\JsonMiddleware::class,
        ],
    ];

    protected $middlewarePriority = [
        \App\Http\Middleware\Authenticate::class,
        \App\Http\Middleware\JsonMiddleware::class,
    ];
}