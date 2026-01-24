<div align="left">
    <a href="https://spatie.be/open-source?utm_source=github&utm_medium=banner&utm_campaign=laravel-csp" target="_blank">
      <picture>
        <source media="(prefers-color-scheme: dark)" srcset="https://spatie.be/packages/header/laravel-csp/html/dark.webp">
        <img alt="Logo for Laravel CSP" src="https://spatie.be/packages/header/laravel-csp/html/light.webp">
      </picture>
    </a>

<h1>Set content security policy headers in a Laravel app</h1>
    
[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-csp.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-csp)
![GitHub Workflow Status](https://img.shields.io/github/actions/workflow/status/spatie/laravel-csp/run-tests.yml?branch=main&label=tests&style=flat-square)
![Check & fix styling](https://github.com/spatie/laravel-csp/workflows/Check%20&%20fix%20styling/badge.svg)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-csp.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-csp)
    
</div>

By default, all scripts on a webpage are allowed to send and fetch data to any site they want. This can be a security problem. Imagine one of your JavaScript dependencies sends all keystrokes, including passwords, to a third party website.

It's very easy for someone to hide this malicious behaviour, making it nearly impossible for you to detect it (unless you manually read all the JavaScript code on your site). For a better idea of why you really need to set content security policy headers, read [this excellent blog post](https://medium.com/hackernoon/im-harvesting-credit-card-numbers-and-passwords-from-your-site-here-s-how-9a8cb347c5b5) by [David Gilbertson](https://twitter.com/D__Gilbertson).

Setting Content Security Policy headers helps solve this problem. These headers dictate which sites your site is allowed to contact. This package makes it easy for you to set the right headers.

This readme does not aim to fully explain all the possible usages of CSP and its directives. We highly recommend that you read [Mozilla's documentation on the Content Security Policy](https://developer.mozilla.org/en-US/docs/Web/HTTP/CSP) before using this package. Another good resource to learn about CSP, is [this edition of the Larasec newsletter](https://larasec.substack.com/p/in-depth-content-security-policy) by Stephen Rees-Carter.


## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/laravel-csp.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/laravel-csp)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require spatie/laravel-csp
```

You can publish the config-file with:

```bash
php artisan vendor:publish --tag=csp-config
```

This is the contents of the file which will be published at `config/csp.php`:

```php
return [

    /*
     * Presets will determine which CSP headers will be set. A valid CSP preset is
     * any class that implements `Spatie\Csp\Preset`
     */
    'presets' => [
        Spatie\Csp\Presets\Basic::class,
    ],

    /**
     * Register additional global CSP directives here.
     */
    'directives' => [
        // [Directive::SCRIPT, [Keyword::UNSAFE_EVAL, Keyword::UNSAFE_INLINE]],
    ],

    /*
     * These presets which will be put in a report-only policy. This is great for testing out
     * a new policy or changes to existing CSP policy without breaking anything.
     */
    'report_only_presets' => [
        //
    ],

    /**
     * Register additional global report-only CSP directives here.
     */
    'report_only_directives' => [
        // [Directive::SCRIPT, [Keyword::UNSAFE_EVAL, Keyword::UNSAFE_INLINE]],
    ],

    /*
     * All violations against a policy will be reported to this url.
     * A great service you could use for this is https://report-uri.com/
     */
    'report_uri' => env('CSP_REPORT_URI', ''),

    /*
     * Headers will only be added if this setting is set to true.
     */
    'enabled' => env('CSP_ENABLED', true),

    /**
     * Headers will be added when Vite is hot reloading.
     */
    'enabled_while_hot_reloading' => env('CSP_ENABLED_WHILE_HOT_RELOADING', false),

    /*
     * The class responsible for generating the nonces used in inline tags and headers.
     */
    'nonce_generator' => Spatie\Csp\Nonce\RandomString::class,

    /*
     * Set false to disable automatic nonce generation and handling.
     * This is useful when you want to use 'unsafe-inline' for scripts/styles
     * and cannot add inline nonces.
     * Note that this will make your CSP policy less secure.
     */
    'nonce_enabled' => env('CSP_NONCE_ENABLED', true),
];
```

You can add CSP headers to all responses of your app by registering `Spatie\Csp\AddCspHeaders::class` as global middleware in `bootstrap/app.php`.

```php
use Spatie\Csp\AddCspHeaders;

->withMiddleware(function (Middleware $middleware) {
     $middleware->append(AddCspHeaders::class);
})
```
 
Alternatively you can apply the middleware on the route or route group level.

```php
// In your routes file
Route::get('my-page', 'MyController')
    ->middleware(AddCspHeaders::class);
```

You can also pass a preset class as a parameter to the middleware:
 
```php
// In your routes file
Route::get('my-page', 'MyController')
    ->middleware(AddCspHeaders::class . ':' . MyPreset::class);
``` 

The given preset will override the ones configured in the `config/csp.php` config file for that specific route or group of routes.

Alternatively, you can register your CSP policies as a meta tag using our Blade directives.

```blade
{{-- app/layout.blade.php --}}
<head>
    @cspMetaTag
</head>
```

## Usage

This package ships with a few commonly used presets to get your started. *We're happy to receive PRs for more services!*

| Policy                     | Services                                                                                       |
|----------------------------|------------------------------------------------------------------------------------------------|
| `Basic`                    | Allow requests to scripts, images… within the application                                      |
| `AdobeFonts`               | [fonts.adobe.com](https://fonts.adobe.com) (previously typekit.com)                            |
| `Alchemer Survey`          | [alchemer.com](https://www.alchemer.com)                                                       |
| `Algolia`                  | [algolia.com](https://www.algolia.com)                                                         |
| `Bootstrap`                | [getbootstrap.com](https://getbootstrap.com)                                                   |       
| `Bunny Fonts`              | [fonts.bunny.net](https://fonts.bunny.net/)                                                    |       
| `Chargebee`                | [chargebee.com](https://www.chargebee.com/)                                                    |
| `Cloudflare Cdn`           | [cloudflare.com](https://www.cloudflare.com/en-in/application-services/products/cdn/)          |
| `Cloudflare Turnstile`     | [cloudflare.com](https://www.cloudflare.com/application-services/products/turnstile/)          |
| `Cloudflare Web Analytics` | [cloudflare.com](https://developers.cloudflare.com/web-analytics/)                             |
| `Fathom`                   | [usefathom.com](https://usefathom.com)                                                         |
| `Google TLD's`             | Allow all Google Top Level Domains for 'connect' and 'image'                                   |       
| `Google`                   | Google Analytics & Tag Manager                                                                 |       
| `GoogleFonts`              | [fonts.google.com](https://fonts.google.com)                                                   | 
| `GoogleLookerStudio`       | [lookerstudio.google.com](https://lookerstudio.google.com)                                     | 
| `GoogleMaps`               | [maps.google.com](https://maps.google.com)                                                     | 
| `GoogleRecaptcha`          | [developers.google.com](https://developers.google.com/recaptcha)                               | 
| `Hcaptcha`                 | [hcaptcha.com](https://docs.hcaptcha.com)                                                      |
| `Heap Analytics`           | [heap.io](https://www.heap.io/)                                                                |
| `Hireroad`                 | [hireroad.com](https://hireroad.com)                                                           |
| `Hotjar`                   | [hotjar.com](https://help.hotjar.com/hc/en-us/articles/115011640307-Content-Security-Policies) | 
| `HubSpot`                  | [hubspot.com](https://hubspot.com) (full suite)                                                |       
| `Intercom`                 | [intercom.com](https://intercom.com/)                                                          |       
| `JsDelivr`                 | [jsdelivr.com](https://jsdelivr.com)                                                           |  
| `JQuery`                   | [jquery.com](https://jquery.com)                                                               |  
| `Maze`                     | [maze.co](https://maze.co)                                                                     |       
| `Meta Pixel`               | [facebook.com](https://en-gb.facebook.com/business/tools/meta-pixel)                           |       
| `Microsoft Clarity`        | [clarity.microsoft.com](https://clarity.microsoft.com)                                         |
| `Plain`                    | [plain.com](https://plain.com)                                                                 |
| `Plausible Analytics`      | [plausible.io](http://plausible.io/)                                                           |
| `Posthog`                  | [posthog.com](https://posthog.com/)                                                            |       
| `Rollbar`                  | [posthog.com](https://docs.rollbar.com/docs/javascript)                                        |       
| `Sentry`                   | [sentry.io](https://sentry.io/)                                                                |
| `Stripe`                   | [stripe.com](https://stripe.com/)                                                              |
| `SurveyMonkey`             | [surveymonkey.com](https://www.surveymonkey.com/)                                              |
| `TicketTailor`             | [tickettailor.com](https://www.tickettailor.com)                                               |
| `Tolt`                     | [tolt.io](https://tolt.io)                                                                     |
| `TrackJS`                  | [trackjs.com](https://trackjs.com)                                                             |
| `Vimeo`                    | [vimeo.com](https://vimeo.com)                                                                 |
| `Visual Website Optimizer` | [vwo.com](https://vwo.com)                                                                     |
| `Whereby`                  | [whereby.com](https://whereby.com)                                                             |

Register the presets you want to use for your application in `config/csp.php` under the `presets` or `report_only_presets` key.

If you have app-specific needs or the service you're integrated isn't included in this package, you can create your own preset as explained below. You can also register global directives in the configuration file using a tuple notation.

```php
'directives' => [
    [Directive::SCRIPT, Keyword::UNSAFE_EVAL],
],

'report_only_directives' => [
    [Directive::SCRIPT, Keyword::UNSAFE_INLINE],
],
```

Here you may also create multiple directive & value combinations by padding multiple values in the tuple.

```php
'directives' => [
    [[Directive::SCRIPT, Directive::STYLE], [Keyword::UNSAFE_EVAL, Keyword::UNSAFE_INLINE]],
],
```

## Creating a preset

An example of a CSP directive is `script-src`. If this has the value `'self' www.google.com` then your site can only load scripts from its own domain or `www.google.com`. You'll find [a list with all CSP directives](https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/#Directives) at Mozilla's excellent developer site.

According to the spec certain directive values need to be surrounded by quotes. Examples of this are `'self'`, `'none'` and `'unsafe-inline'`. When using `add` function you're not required to surround the directive value with quotes manually. We will automatically add quotes. Script/style hashes, as well, will be auto-detected and surrounded with quotes.

```php
public function configure(Policy $policy): void
{
    $policy
        // Will output `'self'` when outputting headers
        ->add(Directive::SCRIPT, Keyword::SELF)
        // Will output `'sha256-hash'` when outputting headers
        ->add(Directive::STYLE, 'sha256-hash');
}
```

You may also use the same keywords for multiple directives by passing an array of directives.

```php
public function configure(Policy $policy): void
{
    $policy->add([Directive::SCRIPT, DIRECTIVE::STYLE], 'www.google.com');
}
```

Or multiple keywords for one or more directives.

```php
public function configure(Policy $policy): void
{
    $policy
        ->add(Directive::SCRIPT, [Keyword::UNSAFE_EVAL, Keyword::UNSAFE_INLINE])
        ->add([Directive::SCRIPT, DIRECTIVE::STYLE], ['www.google.com', 'analytics.google.com']);
}
```

There are also a few cases where you don't have to or don't need to specify a value, eg. upgrade-insecure-requests, block-all-mixed-content, ... In this case you can use the following value:

```php
public function configure(Policy $policy): void
{
    $policy
        ->add(Directive::UPGRADE_INSECURE_REQUESTS, Value::NO_VALUE)
        ->add(Directive::BLOCK_ALL_MIXED_CONTENT, Value::NO_VALUE);
}
```

This will output a CSP like this:
```
Content-Security-Policy: upgrade-insecure-requests;block-all-mixed-content
```

The `presets` key of the `csp` config file is set to `[\Spatie\Csp\Presets\Basic::class]` by default. This class allows your site to only use images, scripts, form actions of your own site.

```php
namespace Spatie\Csp\Presets;

use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;

class Basic implements Preset
{
    public function configure(Policy $policy): void
    {
        $policy
            ->add(Directive::BASE, Keyword::SELF)
            ->add(Directive::CONNECT, Keyword::SELF)
            ->add(Directive::DEFAULT, Keyword::SELF)
            ->add(Directive::FORM_ACTION, Keyword::SELF)
            ->add(Directive::IMG, Keyword::SELF)
            ->add(Directive::MEDIA, Keyword::SELF)
            ->add(Directive::OBJECT, Keyword::NONE)
            ->add(Directive::SCRIPT, Keyword::SELF)
            ->add(Directive::STYLE, Keyword::SELF)
            ->addNonce(Directive::SCRIPT)
            ->addNonce(Directive::STYLE);
    }
}
```

You can allow fetching scripts from `www.google.com` by writing a custom preset.

```php
namespace App\Support;

use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;

class MyCspPreset implements Preset
{
    public function configure(Policy $policy): void
    {
        $policy->add(Directive::SCRIPT, 'www.google.com');
    }
}
```

Don't forget to update the `presets` key in the `csp` config file to the class name of your preset.

```php
'presets' => [
    Spatie\Csp\Presets\Basic::class,
    App\Support\MyCspPreset::class,
],
```

### Using inline scripts and styles

When using CSP you must specifically allow the use of inline scripts or styles. The recommended way of doing that with this package is to use a `nonce`. A nonce is a number that is unique per request. The nonce must be specified in the CSP headers and in an attribute on the html tag. This way an attacker has no way of injecting malicious scripts or styles.

First you must add the nonce to the right directives in your policy:

```php
public function configure(Policy $policy): void
{
    $policy
        ->add(Directive::SCRIPT, 'self')
        ->add(Directive::STYLE, 'self')
        ->addNonce(Directive::SCRIPT)
        ->addNonce(Directive::STYLE);
}
```

Next you must add the nonce to the html:

```blade
<style @cspNonce>
   ...
</style>

<script @cspNonce>
   ...
</script>
```

There are few other options to use inline styles and scripts. Take a look at the [CSP docs on the Mozilla developer site](https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/script-src) to know more.

### Integration with Vite

When building assets, Laravel's Vite plugin can [generate a nonce](https://laravel.com/docs/9.x/vite#content-security-policy-csp-nonce) that you can retrieve with `Vite::cspNonce`.  You can use in your own `NonceGenerator`.

```php
namespace App\Support;

use Illuminate\Support\Facades\Vite;
use Spatie\Csp\Nonce\NonceGenerator;

class LaravelViteNonceGenerator implements NonceGenerator
{
    public function generate(): string
    {
        return Vite::cspNonce();
    }
}
```

Don't forget to specify the fully qualified class name of your `NonceGenerator` in the `nonce_generator` key of the `csp` config file.

Alternatively, you can instruct Vite to use a specific value that it should use as nonce.

```php
namespace App\Support;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Vite;

class RandomString implements NonceGenerator
{
    public function generate(): string
    {
        // Determine the value for `$myNonce` however you want
        $myNonce = '';
    
        Vite::useCspNonce($myNonce);
        
        return $myNonce;
    }
}
```

The generated nonce should be a **base64-value** derived from at least **16 bytes of secure random data**
This limits the character set to characters safe for use in HTML attributes and HTTP headers.
For more details, see the [W3C Content Security Policy Level 3 specification](https://www.w3.org/TR/CSP3/#grammardef-base64-value)

### Outputting a CSP Policy as a meta tag

In rare circumstances, a large site may have so many external connections that the CSP header actually exceeds the max header size. Or you might be generating a static page with Laravel and don't have control over the headers when the response is sent. Thankfully, the [CSP specification](https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy#using_the_html_meta_element) allows for outputting information as a meta tag in the head of a webpage.

This package provides a `@cspMetaTag` blade directive that you may place in the `<head>` of your site. This will render a header for all configured presets (both default and report-only).

```blade
<head>
    @cspMetaTag
</head>
```

You may also use this tag to render a specific preset.

```blade
<head>
    @cspMetaTag(App\Support\MyCustomPreset::class)
</head>
```

Or use the `@cspMetaTagReportOnly` tag to render a specific preset in report-only mode.

```blade
<head>
    @cspMetaTagReportOnly(App\Support\MyCustomPreset::class)
</head>
```

### Reporting CSP errors

#### In the browser

Instead of outright blocking all violations, you can put configure a CSP policy in report only mode by registering presets in the `report_only_presets` configuration option. In this case all requests will be made, but all violations will display in your favourite browser's console.

#### To an external url

Any violations against the policy can be reported to a given url. You can set that url in the `report_uri` key of the `csp` config file. A great service that is specifically built for handling these violation reports is [http://report-uri.io/](http://report-uri.io/). 

### Testing

You can run all the tests with:

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

### Security

If you've found a bug regarding security please mail [security@spatie.be](mailto:security@spatie.be) instead of using the issue tracker.

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [Sebastian De Deyne](https://github.com/sebastiandedeyne)
- [Thomas Verhelst](https://github.com/TVke)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
