<?php

namespace App\Http\Middleware;

use Closure;

class CorsMiddleware
{
    // Middleware for CORS
public function handle($request, Closure $next)
{
    return $next($request)
        ->header('Access-Control-Allow-Origin', 'http://45.55.134.122') // Adjust this to your front-end domain
        ->header('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE')
        ->header('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With, Application');
}

}
