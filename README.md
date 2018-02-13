# Set up the Content Security Policy header with ease.

Safety on the web is an ever growing topic and the `Content-Security-Policy` header is a way to block outsiders from getting on your site.
This package is not a one-size-fit-all solution, but it makes the setup easier by having a few common setups: 

- `strict`: A strict setup where you have no outside dependencies.
- `basic`: A setup with the most common needed parts like google analytics, youtube and google fonts. 
- `custom`: Of course you can create your own setups. [Usage](https://github.com/spatie/laravel-csp#usage)


## Installation

You can install the package via composer:

```bash
composer require spatie/laravel-csp
```

You can publish the config-file with:

```bash
php artisan vendor:publish --provider="Spatie\LaravelCsp\LaravelCspServiceProvider" --tag="config"
```

This is the contents of the file which will be published at `config/csp.php`:

``` php
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
         * how-to implement: https://developers.google.com/analytics/devguides/collection/analyticsjs/sending-hits#specifying_different_transport_mechanisms
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
```

And finally you should install the provided middleware \Spatie\LaravelCsp\Middleware\CspHeaderMiddleware::class in the http kernel.

```php
// app/Http/Kernel.php

...

protected $middlewareGroups = [
   'web' => [
       ...
       \Spatie\LaravelCsp\Middlewares\CSPHeaderMiddleware::class,
   ],
```
 
## Usage

You can create your custom CSP setup very easy by declaring your `CustomCSP` class that uses the `CspSetup` trait.

You can put your own custom setups together in the `setups` array, with a suitable name, or you can use one of the prefabs.

The setup used is the one declared in your `.env` file with the name `CSP_SETUP`.

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email freek@spatie.be instead of using the issue tracker.

## Postcardware

You're free to use this package, but if it makes it to your production environment we highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using.

Our address is: Spatie, Samberstraat 69D, 2060 Antwerp, Belgium.

We publish all received postcards [on our company website](https://spatie.be/en/opensource/postcards).

## Credits

- [Thomas Verhelst](https://github.com/TVke)
- [All Contributors](../../contributors)

## Support us

Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

Does your business depend on our contributions? Reach out and support us on [Patreon](https://www.patreon.com/spatie). 
All pledges will be dedicated to allocating workforce on maintenance and new awesome stuff.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
