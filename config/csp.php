<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Content Security Policy Setups
    |--------------------------------------------------------------------------
    |
    | Here are the most common Content Security Policy setups you can use
    | in your application. The default setting is the strictest to be
    | safe. Specific variables can be set in the environment file.
    |
    */

    'options' => [
        'Content-Security-Policy' => env('', 'none'),
        'base-uri' => env('', 'none'),
        'block-all-mixed-content' => env('', 'none'),
        'child-src' => env('', 'none'),
        'connect-src' => env('', 'none'),
        'default-src' => env('', 'none'),
        'disown-opener' => env('', 'none'),
        'font-src' => env('', 'none'),
        'form-action' => env('', 'none'),
        'frame-ancestors' => env('', 'none'),
        'frame-src' => env('', 'none'),
        'img-src' => env('', 'none'),
        'manifest-src' => env('', 'none'),
        'media-src' => env('', 'none'),
        'navigation-to' => env('', 'none'),
        'object-src' => env('', 'none'),
        'plugin-types' => env('', 'none'),
        'referrer' => env('', 'none'),
        'report-sample' => env('', 'none'),
        'report-to' => env('', 'none'),
        'report-uri' => env('', 'none'),
        'require-sri-for' => env('', 'none'),
        'sandbox' => env('', 'none'),
        'script-src' => env('', 'none'),
        'strict-dynamic' => env('', 'none'),
        'style-src' => env('', 'none'),
        'upgrade-insecure-requests' => env('', 'none'),
        'worker-src' => env('', 'none'),
    ],
];
