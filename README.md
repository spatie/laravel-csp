**WORK IN PROGRESS, DO NOT USE YET**

# Add CSP headers to the responses of a Laravel app

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-csp.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-csp)
[![Build Status](https://img.shields.io/travis/spatie/laravel-csp/master.svg?style=flat-square)](https://travis-ci.org/spatie/laravel-csp)
[![StyleCI](https://styleci.io/repos/119958264/shield?branch=master)](https://styleci.io/repos/119958264)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/laravel-csp.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/laravel-csp)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-csp.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-csp)

By default all scripts on a webpage are allowed to fetch and send data to any site they want. This can be a security problem. Imagine on of your JavaScript dependencies sends all keystrokes (so including passwords) to a third party website. It's also very easy to hide this behaviour, make it nearly impossible for you to detect it (unless you manually read all the JavaScript code on your site). For more info on the subject read [this excellent blog post](TODO: add link) by [xxx](TODO: add link). 

The solution to this problem is setting Content Security Policy headers. These headers dictate which sites your site is allowed to contact. This package makes it easy for you to set the right headers.


## Installation

You can install the package via composer:

```bash
composer require spatie/laravel-csp
```

You can publish the config-file with:

```bash
php artisan vendor:publish --provider="Spatie\Csp\CspServiceProvider" --tag="config"
```

This is the contents of the file which will be published at `config/csp.php`:

```php
return [

    /*
     * A csp profile will determine which csp headers will be set.
     */
    'profile' => '',

    /*
     * This profile which will be put in report only mode. This is great for testing out
     * a new profile or changes to existing csp policy without breaking anyting.
     */
    'report_only_profile' => \Spatie\Csp\Profiles\Basic::class,

    /*
     * All violations against the csp policy will be reported to this url.
     * A great service you could use for this is https://report-uri.com/
     *
     * You can override this setting by calling `reportTo` on your profile.
     */
    'report_uri' => env('CSP_REPORT_URI', ''),

    /*
     * Headers will only be added if this setting is enabled
     */
    'enabled' => env('CSP_ENABLED', true),
];
```

You can add csp headers to all responses of your app by registering `\Spatie\Csp\AddCspHeaders::class` in the http kernel.

```php
// app/Http/Kernel.php

...

protected $middlewareGroups = [
   'web' => [
       ...
       \Spatie\Csp\AddCspHeaders::class,
   ],
```
 
Alternatively you can apply the middelware on the route of route group level.

```php
// in a routes file

Route::get('my-page', 'MyController')->middleware(Spatie\Csp\AddCspHeaders::class);
```

You can also pass a profile class as a parameter to the middleware:
 
```php
// in a routes file

Route::get('my-page', 'MyController')->middleware(Spatie\Csp\AddCspHeaders::class . ':' . MyProfile::class);
``` 

This profile will override the one configured in the config file for that specific route or group of routes.

 
## Usage

This package allows you to define csp profiles. A csp profile determines which csp directives should be used. 

An example of a csp directive is `script-src`. If this has the value `'self' www.google.com` then your site can only load scripts from it's own domain of `www.google.com`. You'll find [a list with all csp directives](https://www.w3.org/TR/CSP3/#csp-directives) at Mozilla's excellent developer site.

## Creating custom profiles

In the `profile` key of the `csp` config file is set to `\Spatie\Csp\Profiles\Basic::class` by default. This class allows your site to only use images, scripts, form actions of your own site. This is how the class looks like.

```php
namespace Spatie\Csp\Profiles;

use Spatie\Csp\Directive;

class Basic extends Profile
{
    public function configure()
    {
        $this
            ->addDirective(Directive::CONNECT, "'self'")
            ->addDirective(Directive::DEFAULT, "'self'")
            ->addDirective(Directive::FORM_ACTION, "'self'")
            ->addDirective(Directive::IMG, "'self'")
            ->addDirective(Directive::MEDIA, "'self'")
            ->addDirective(Directive::SCRIPT, "'self'")
            ->addDirective(Directive::STYLE, "'self'");
    }
}
```

You can allow fetching scripts from `www.google.com` by extending this class:

```php
namespace App\Services\CspProfiles;

use Spatie\Csp\Directive;
use Spatie\Csp\Profiles\Profile;

class MyCustomProfile extends Profile
{
    public function configure()
    {
        parent::configure();
        
        $this->addDirective(Directive::SCRIPT, 'www.google.com');
    }
}
```

Don't forget to set the `profile` key in the `csp` config file to the class name of your profile (in this case it would be `App\Services\CspProfiles\MyCustomProfile`).


### Reporting csp errors

#### In the browser

Instead of downright blocking all violations you can put a profile in report only mode. In this case all requests will be made, but you'll see all violations will be displaying in your favourite browser's console.

To put a profile in report only mode just call `reportOnly()` in the `configure()` function of a report:

```php
    public function configure()
    {
        parent::configure();
        
        $this->reportOnly();
    }
```

#### To an external url

Any violations against to the policy can be reported to a given url. You can set that url in the `report_uri` key of the `csp` config file. A great service that is specifically built for handling these violation reports is [http://report-uri.io/](http://report-uri.io/). 

#### Using multipe profiles

To test out changes to your csp policy you can specify a second profile in the `report_only_profile` in the `csp` config key. The profile specified in `profile` will be enforced, the one in `report_only_profile` will not. This is great for testing out a new profile or changes to existing csp policy without breaking anyting.

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

### Warnings

- `img-src: https://analytics.google.be` is unsafe, use beacon or XHR method instead of the default image method. ([How-to](https://developers.google.com/analytics/devguides/collection/gtagjs/sending-data#specify_different_transport_mechanisms) [exploit info](https://githubengineering.com/githubs-post-csp-journey/#img-src---how-scary-can-an-image-really-be))

- try avoiding unsafe CDNs ([exploit info](https://github.com/cure53/XSSChallengeWiki/wiki/H5SC-Minichallenge-3:-%22Sh*t,-it%27s-CSP!%22#conclusion))
Sadly, the Google API is not strict enough in terms of scripts and data that can be pulled from it. So it classifies as insecure CDN. 

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
- `Directive::report`: `'report-uri'` (sends any violation in a `POST` request to the specified location)
- `Directive::sandbox`: `'sandbox'`
- `Directive::script`: `'script-src'`
- `Directive::style`: `'style-src'`
- `Directive::upgrade`: `'upgrade-insecure-requests'`
- `Directive::worker`: `'worker-src'`

### Testing

To test your `Content-Security-Policy` there is a `CSP_REPORT` toggle that can be set in the .env file, don't forget to disable it when your CSP header is ready.

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
