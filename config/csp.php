<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Content Security Policy Setups
    |--------------------------------------------------------------------------
    |
    | Here are some common Content Security Policy setups you can use in your
    | application. The default setup is the strictest to be safe. A custom
    | setup can be declared here below and controlled in the .env file.
    |
    */

    'default' => env('CSP_SETUP', 'strict'),

    'setups' => [
        'strict' => ['base'],
        'basic' => ['base', 'google analytics', 'google fonts', 'youtube'],
        'custom' => ['base', 'pdf', 'google analytics', 'font awesome fonts', 'codepen', 'pusher'],
    ],

    'setup-parts' => [

        'base' => [
            'default-src' => ['none'],
            'connect-src' => ['self'],
            'form-action' => ['self'],
            'img-src' => ['self'],
            'script-src' => ['self'],
            'style-src' => ['self'],
            'media-src' => ['self'],
        ],

        /*
         * content from the main domain
         */
        'pdf' => [
            'plugin-types' => ['application/pdf'],
        ],
        'java-applet' => [
            'plugin-types' => ['application/x-java-applet'],
        ],

        /*
         * analytics
         *
         * google analytics standard uses the image approach, this contains some risks,
         * consider using google analytics XHR approach and delete the img-src below
         * how-to implement: https://developers.google.com/analytics/devguides/collection/gtagjs/sending-data#specify_different_transport_mechanisms
         */
        'google analytics' => [
            'connect-src' => ['www.google-analytics.com'],
            'script-src' => ['www.google-analytics.com', 'www.googletagmanager.com'],
            'img-src' => ['www.google-analytics.com'],
        ],

        /*
         * fonts
         */
        'base64 fonts' => [
            'font-src' => ['data:'],
        ],

        'google fonts' => [
            'font-src' => ['fonts.gstatic.com'],
            'style-src' => ['fonts.googleapis.com'],
        ],

        'font awesome fonts' => [
            'font-src' => ['use.fontawesome.com'],
            'style-src' => ['use.fontawesome.com'],
        ],

        /*
         * embeds
         */
        'youtube' => [
            'frame-src' => ['www.youtube.com'],
            'worker-src' => ['codepen.io'],
            'child-src' => ['codepen.io'],
        ],

        'codepen' => [
            'frame-src' => ['codepen.io'],
            'worker-src' => ['codepen.io'],
            'child-src' => ['codepen.io'],
        ],

        /*
         * web sockets
         */
        'pusher' => [
            'connect-src' => ['*.pusher.com'],
            'script-src' => ['stats.pusher.com'],
        ],

        /*
         * CDNs (Warning) try avoiding CDNs in combination with CSP
         * google API: https://github.com/cure53/XSSChallengeWiki/wiki/H5SC-Minichallenge-3:-%22Sh*t,-it%27s-CSP!%22#submissions
         */
        'google API' => [
            'connect-src' => ['ajax.googleapis.com'],
        ],

        'yahoo API' => [
            'connect-src' => ['query.yahooapis.com'],
        ],

    ],

];
