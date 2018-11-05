# Set content security policy headers in a Laravel app

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-csp.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-csp)
[![Build Status](https://img.shields.io/travis/spatie/laravel-csp/master.svg?style=flat-square)](https://travis-ci.org/spatie/laravel-csp)
[![StyleCI](https://styleci.io/repos/119958264/shield?branch=master)](https://styleci.io/repos/119958264)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/laravel-csp.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/laravel-csp)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-csp.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-csp)

By default all scripts on a webpage are allowed to send and fetch data to any site they want. This can be a security problem. Imagine one of your JavaScript dependencies sends all keystrokes, including passwords, to a third party website.

It's very easy for someone to hide this malicious behaviour, making it nearly impossible for you to detect it (unless you manually read all the JavaScript code on your site). For a better idea of why you really need to set content security policy headers read [this excellent blog post](https://hackernoon.com/im-harvesting-credit-card-numbers-and-passwords-from-your-site-here-s-how-9a8cb347c5b5) by [David Gilbertson](https://twitter.com/D__Gilbertson).

Setting Content Security Policy headers helps solve this problem. These headers dictate which sites your site is allowed to contact. This package makes it easy for you to set the right headers.

This readme does not aim to fully explain all the possible usages of CSP and it's directives. We highly recommend that you read [Mozilla's documentation on the Content Security Policy](https://developer.mozilla.org/en-US/docs/Web/HTTP/CSP)) before using this package.

If you're an audio visual learner you should check out this video on how to use this package.
https://www.laraning.com/videos/spatie-csp-content-security-policy

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
     * A policy will determine which CSP headers will be set. A valid CSP policy is
     * any class that extends `Spatie\Csp\Policies\Policy`
     */
    'policy' => Spatie\Csp\Policies\Basic::class,

    /*
     * This policy which will be put in report only mode. This is great for testing out
     * a new policy or changes to existing csp policy without breaking anything.
     */
    'report_only_policy' => '',

    /*
     * All violations against the policy will be reported to this url.
     * A great service you could use for this is https://report-uri.com/
     *
     * You can override this setting by calling `reportTo` on your policy.
     */
    'report_uri' => env('CSP_REPORT_URI', ''),

    /*
     * Headers will only be added if this setting is set to true.
     */
    'enabled' => env('CSP_ENABLED', true),

    /*
     * The class responsible for generating the nonces used in inline tags and headers.
     */
    'nonce_generator' => Spatie\Csp\Nonce\RandomString::class,
];
```

You can add CSP headers to all responses of your app by registering `Spatie\Csp\AddCspHeaders::class` in the http kernel.

```php
// app/Http/Kernel.php

...

protected $middlewareGroups = [
   'web' => [
       ...
       \Spatie\Csp\AddCspHeaders::class,
   ],
```
 
Alternatively you can apply the middleware on the route or route group level.

```php
// in a routes file
Route::get('my-page', 'MyController')->middleware(Spatie\Csp\AddCspHeaders::class);
```

You can also pass a policy class as a parameter to the middleware:
 
```php
// in a routes file
Route::get('my-page', 'MyController')->middleware(Spatie\Csp\AddCspHeaders::class . ':' . MyPolicy::class);
``` 

The given policy will override the one configured in the config file for that specific route or group of routes.

## Usage

This package allows you to define CSP policies. A CSP policy determines which CSP directives will be set in the headers of the response. 

An example of a CSP directive is `script-src`. If this has the value `'self' www.google.com` then your site can only load scripts from it's own domain of `www.google.com`. You'll find [a list with all CSP directives](https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/#Directives) at Mozilla's excellent developer site.

According to the spec certain directive values need to be surrounded by quotes. Examples of this are `'self'`, `'none'` and `'unsafe-inline'`. When using `addDirective` function you're not required to surround the directive value with quotes manually. We will automatically add quotes. Script/style hashes, as well, will be auto-detected and surrounded with quotes.

```php
// in a policy
...
   ->addDirective(Directive::SCRIPT, Keyword::SELF) // will output `'self'` when outputting headers
   ->addDirective(Directive::STYLE, 'sha256-hash') // will output `'sha256-hash'` when outputting headers
...
```

You can add multiple policy options in the same directive giving an array as second parameter to `addDirective` or a single string in which every option is separated by one or more spaces.

```php
// in a policy
...
   ->addDirective(Directive::SCRIPT, [
       Keyword::STRICT_DYNAMIC,
       Keyword::SELF,
       'www.google.com',
   ])
   ->addDirective(Directive::SCRIPT, 'strict-dynamic self  www.google.com')
   // will both output `'strict_dynamic' 'self' www.google.com` when outputting headers
...
```

There are also a few cases where you don't have to or don't need to specify a value, eg. upgrade-insecure-requests, block-all-mixed-content, ... In this case you can use the following value:

```php
// in a policy
...
    ->addDirective(Directive::UPGRADE_INSECURE_REQUESTS, Value::NO_VALUE)
    ->addDirective(Directive::BLOCK_ALL_MIXED_CONTENT, Value::NO_VALUE);
...
```

This will output a CSP like this:
```
Content-Security-Policy: upgrade-insecure-requests;block-all-mixed-content
```


### Creating policies

In the `policy` key of the `csp` config file is set to `\Spatie\Csp\Policies\Basic::class` by default. This class allows your site to only use images, scripts, form actions of your own site. This is how the class looks like.

```php
namespace Spatie\Csp\Policies;

use Spatie\Csp\Directive;
use Spatie\Csp\Value;

class Basic extends Policy
{
    public function configure()
    {
        $this
            ->addDirective(Directive::BASE, Keyword::SELF)
            ->addDirective(Directive::CONNECT, Keyword::SELF)
            ->addDirective(Directive::DEFAULT, Keyword::SELF)
            ->addDirective(Directive::FORM_ACTION, Keyword::SELF)
            ->addDirective(Directive::IMG, Keyword::SELF)
            ->addDirective(Directive::MEDIA, Keyword::SELF)
            ->addDirective(Directive::OBJECT, Keyword::NONE)
            ->addDirective(Directive::SCRIPT, Keyword::SELF)
            ->addDirective(Directive::STYLE, Keyword::SELF)
            ->addNonceForDirective(Directive::SCRIPT)
            ->addNonceForDirective(Directive::STYLE);
    }
}
```

You can allow fetching scripts from `www.google.com` by extending this class:

```php
namespace App\Services\Csp\Policies;

use Spatie\Csp\Directive;
use Spatie\Csp\Policies\Basic;

class MyCustomPolicy extends Basic
{
    public function configure()
    {
        parent::configure();
        
        $this->addDirective(Directive::SCRIPT, 'www.google.com');
    }
}
```

Don't forget to set the `policy` key in the `csp` config file to the class name of your policy (in this case it would be `App\Services\Csp\Policies\MyCustomPolicy`).

### Using inline scripts and styles

When using CSP you must specifically allow the use of inline scripts or styles. The recommended way of doing that with this package is to use a `nonce`. A nonce is a number that is unique per request. The nonce must be specified in the CSP headers and in an attribute on the html tag. This way an attacker has no way of injecting malicious scripts or styles.

First you must add the nonce to the right directives in your policy:

```php
// in a policy

public function configure()
  {
      $this
        ->addDirective(Directive::SCRIPT, 'self')
        ->addDirective(Directive::STYLE, 'self')
        ->addNonceForDirective(Directive::SCRIPT)
        ->addNonceForDirective(Directive::STYLE)
        ...
}
```

Next you must add the nonce to the html:

```
{{-- in a view --}}
<style nonce="{{ csp_nonce() }}">
   ...
</style>

<script nonce="{{ csp_nonce() }}">
   ...
</script>
```

There are few other options to use inline styles and scripts. Take a look at the [CSP docs on the Mozilla developer site](https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/script-src) to know more.


### Reporting CSP errors

#### In the browser

Instead of outright blocking all violations you can put a policy in report only mode. In this case all requests will be made, but all violations will display in your favourite browser's console.

To put a policy in report only mode just call `reportOnly()` in the `configure()` function of a report:

```php
public function configure()
{
    parent::configure();
    
    $this->reportOnly();
}
```

#### To an external url

Any violations against to the policy can be reported to a given url. You can set that url in the `report_uri` key of the `csp` config file. A great service that is specifically built for handling these violation reports is [http://report-uri.io/](http://report-uri.io/). 

#### Using multiple policies

To test changes to your CSP policy you can specify a second policy in the `report_only_policy` in the `csp` config key. The policy specified in `policy` will be enforced, the one in `report_only_policy` will not. This is great for testing a new policy or changes to existing CSP policy without breaking anything.

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

- [Freek Van der Herten](https://github.com/freekmurze)
- [Thomas Verhelst](https://github.com/TVke)
- [All Contributors](../../contributors)

## Support us

Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

Does your business depend on our contributions? Reach out and support us on [Patreon](https://www.patreon.com/spatie). 
All pledges will be dedicated to allocating workforce on maintenance and new awesome stuff.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
