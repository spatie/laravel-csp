**WORK IN PROGRESS, DO NOT USE YET**

# Add CSP headers to the responses of a Laravel app

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-csp.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-csp)
[![Build Status](https://img.shields.io/travis/spatie/laravel-csp/master.svg?style=flat-square)](https://travis-ci.org/spatie/laravel-csp)
[![StyleCI](https://styleci.io/repos/119958264/shield?branch=master)](https://styleci.io/repos/119958264)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/laravel-csp.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/laravel-csp)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-csp.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-csp)

By default all scripts on a webpage are allowed to fetch and send data to any site they want. This can be a security problem. Imagine on of your JavaScript dependencies sends all keystrokes (so including passwords) to a third party website. It's also very easy to hide this behaviour, make it nearly impossible for you to detect it (unless you manually read all the JavaScript code on your site). To feel why you really need to set content security policy headers read [this excellent blog post](https://hackernoon.com/im-harvesting-credit-card-numbers-and-passwords-from-your-site-here-s-how-9a8cb347c5b5) by [David Gilbertson](https://twitter.com/D__Gilbertson), or head to [Mozilla's Content Security Policy docs](https://developer.mozilla.org/en-US/docs/Web/HTTP/CSP). 

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

### Creating custom profiles

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


### Testing

You can run all the tests with:

```bash
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
