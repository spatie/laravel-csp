<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Content Security Policy Setup
    |--------------------------------------------------------------------------
    |
    | Here are you can specify a Content Security Policy profile class that
    | will be used by the middleware. The default setup is the strictest
    | setup. By setting CSP in the .env file to false you disable it.
    |
    */

    'enabled' => env('CSP', true),

    'csp_profile' => \Spatie\LaravelCsp\Profiles\Strict::class,

    'report_mode' => env('CSP_REPORT', false),

];
