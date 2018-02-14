# Set up the Content Security Policy header with ease.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-csp.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-csp)
[![Build Status](https://img.shields.io/travis/spatie/laravel-csp/master.svg?style=flat-square)](https://travis-ci.org/spatie/laravel-csp)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/laravel-csp.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/laravel-csp)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-csp.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-csp)

Safety on the web is an ever growing problem and by setting the `Content-Security-Policy` header you are one set closer to a more secure user experience. 
Setting the `Content-Security-Policy` header is a way to block outsiders from getting content on your site without your permission. 
The package is by default very strict and can be loosened by creating a custom class and defined in the config file. (see [Usage](https://github.com/spatie/laravel-csp#usage))
This package is not a one-size-fit-all solution, but it makes the setup easier by having a handful of methods with the most common permissions. (see [Allow Functions](https://github.com/spatie/laravel-csp#allow-functions))

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
    | Content Security Policy Setup
    |--------------------------------------------------------------------------
    |
    | Here are you can specify a Content Security Policy profile class that
    | will be used by the middleware. The default setup is the strictest
    | setup. By setting CSP in the .env file to false you disable it.
    |
    */

    'enabled' => env('CSP', true),

    'csp_profile' => \Spatie\LaravelCsp\Profile\Strict::class,

];
```

And finally you should install the provided middleware \Spatie\LaravelCsp\Middleware\CspHeader::class in the http kernel.

```php
// app/Http/Kernel.php

...

protected $middlewareGroups = [
   'web' => [
       ...
       \Spatie\LaravelCsp\Middleware\CSPHeader::class,
   ],
```
 
## Usage

### Custom Setup

You can create your custom CSP setup very easy by declaring your `CustomCsp` class that extends the `Csp` class and implements the `CspInterface` like this:

```php
class CustomCsp extends Csp implements CspInterface
{
    /**
     * Fill this method with the $this->allows methods ||
     * add your own headers with $this->addHeader().
     */
    public function profileSetup()
    {
        $this->allowsGoogleAnalytics();
        $this->allowsGoogleFonts();
        $this->allowsYoutube();
        $this->addHeader(Directive::style, 'https://example.com');
        $this->addHeader(Directive::img, ['https://spatie.be', 'https://example.com']);
    }
}
```

### Allow Function

- `$this->allowsGoogleAnalytics;`: There is no `img-src` set because this is unsafe (see [Warnings](https://github.com/spatie/laravel-csp#warnings)) 
- `$this->allowsBase64Fonts;`
- `$this->allowsGoogleFonts;`
- `$this->allowsFontAwesomeFonts;`
- `$this->allowsCodepen;`
- `$this->allowsPusher;`
- `$this->allowsPdfs;`
- `$this->allowsJavaApplets;`
- `$this->allowsGoogleApi;`: Avoid using this without reading the [Warnings](https://github.com/spatie/laravel-csp#warnings)
- `$this->allowsYahooApi;`: Avoid using this without reading the [Warnings](https://github.com/spatie/laravel-csp#warnings)

### Directives

- `Directive::base`: `'base-uri'`
- `Directive::child`: `'child-src'`
- `Directive::connect`: `'connect-src'`
- `Directive::default`: `'default-src'`
- `Directive::font`: `'font-src'`
- `Directive::form`: `'form-action'`
- `Directive::frame`: `'frame-src'`
- `Directive::frameAncestors`: `'frame-ancestors'`
- `Directive::img`: `'img-src'`
- `Directive::manifest`: `'manifest-src'`
- `Directive::media`: `'media-src'`
- `Directive::mixed`: `'block-all-mixed-content'`
- `Directive::object`: `'object-src'`
- `Directive::plugin`: `'plugin-types'`
- `Directive::report`: `'report-uri'`
- `Directive::sandbox`: `'sandbox'`
- `Directive::script`: `'script-src'`
- `Directive::style`: `'style-src'`
- `Directive::upgrade`: `'upgrade-insecure-requests'`
- `Directive::worker`: `'worker-src'`

### Warnings

- `img-src: https://analytics.google.be` is unsafe, use beacon or XHR method instead of the default image method. ([How-to](https://developers.google.com/analytics/devguides/collection/gtagjs/sending-data#specify_different_transport_mechanisms) [exploit info](https://githubengineering.com/githubs-post-csp-journey/#img-src---how-scary-can-an-image-really-be))

- try avoiding unsafe CDNs ([exploit info](https://github.com/cure53/XSSChallengeWiki/wiki/H5SC-Minichallenge-3:-%22Sh*t,-it%27s-CSP!%22#conclusion))
Sadly, the Google API is not strict enough in terms of scripts and data that can be pulled from it. So it classifies as insecure CDN. 

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
