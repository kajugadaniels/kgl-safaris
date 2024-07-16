<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations are allowed
    | on your web server. This is a security feature that is necessary
    | when working with APIs that are accessed from other websites.
    |
    */

    // Paths you want to run CORS service on
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    // Allows all domains
    'allowed_origins' => ['*'],

    // Matches the request method. `*` allows all methods.
    'allowed_methods' => ['*'],

    // Sets the Access-Control-Allow-Headers response header. `*` allows all headers.
    'allowed_headers' => ['*'],

    // Sets the Access-Control-Expose-Headers response header.
    'exposed_headers' => [],

    // Sets the Access-Control-Allow-Credentials header.
    'supports_credentials' => false,

    // Sets the Access-Control-Max-Age response header.
    'max_age' => 0,

    // Sets the Access-Control-Allow-Origin response header to match the request origin.
    'allowed_origins_patterns' => [],

];
