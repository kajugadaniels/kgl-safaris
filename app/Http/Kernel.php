<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        // Existing global middleware
        \Fruitcake\Cors\HandleCors::class, // Add this line to apply CORS globally
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            // existing web middleware
        ],

        'api' => [
            // existing api middleware
            \Fruitcake\Cors\HandleCors::class, // Add this line to apply CORS only to API routes
        ],
    ];
}
