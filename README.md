# Set content security policy headers in a Laravel app

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-csp.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-csp)
![GitHub Workflow Status](https://img.shields.io/github/actions/workflow/status/spatie/laravel-csp/run-tests.yml?branch=main&label=tests&style=flat-square)
![Check & fix styling](https://github.com/spatie/laravel-csp/workflows/Check%20&%20fix%20styling/badge.svg)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-csp.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-csp)

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

An example of a CSP directive is `script-src`. If this has the value `'self' www.google.com` then your site can only load scripts from it's own domain or `www.google.com`. You'll find [a list with all CSP directives](https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/#Directives) at Mozilla's excellent developer site.

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

In the `policy` key of the `csp` config file is set to `\Spatie\Csp\Policies\Basic::class` by default. This class allows your site to only use images, scripts, form actions of your own site. This is how the class looks:

```php
namespace App\Support;

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
namespace App\Support;

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

Don't forget to set the `policy` key in the `csp` config file to the class name of your policy (in this case it would be `App\Support\MyCustomPolicy`).

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

### Integration with Vite

When building assets, Laravel's Vite plugin can [generate a nonce](https://laravel.com/docs/9.x/vite#content-security-policy-csp-nonce) that you can retrieve with `Vite::cspNonce`.  You can use in your own `NonceGenerator`.

```php
namespace App\Support;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Vite;

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
        $myNonce = ''; // determine the value for `$myNonce` however you want
    
        Vite::useCspNonce($myNonce);
        
        return $myNonce;
    }
}
```

### Outputting a CSP Policy as a meta tag

In rare circumstances, a large site may have so many external connections that the CSP header actually exceeds the max header size.
Thankfully, the [CSP specification](https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy#using_the_html_meta_element) allows for outputting information as a meta tag in the head of a webpage.

To support this use case, this package provides a `@cspMetaTag` blade directive that you may place in the `<head>` of your site.

```blade
<head>
    @cspMetaTag(App\Support\MyCustomPolicy::class)
</head>
```

You should be aware of the following implementation details when using the meta tag blade directive:
- Note that you should manually pass the fully qualified class name of the policy we want to output a meta tag for. 
  The `csp.policy` and `csp.report_only_policy` config options have no effect here.
- Because blade files don't have access to the `Response` object, the `shouldBeApplied` method will have no effect. 
  If you have declared the `@cspMetaTag` directive and the `csp.enabled` config option is set to true, the meta tag will be output regardless.
- Any configuration (such as setting your policy to report only) should be done in the `configure` method of the policy,
  rather than relying on settings in the `csp` config file. The `csp.report_uri` option will be respected, so there is no need to configure that manually.

### Reporting CSP errors

#### In the browser

Instead of outright blocking all violations, you can put a policy in report only mode. In this case all requests will be made, but all violations will display in your favourite browser's console.

To put a policy in report only mode just call `reportOnly()` in the `configure()` function of a report:

```php
public function configure()
{
    parent::configure();
    
    $this->reportOnly();
}
```

#### To an external url

Any violations against the policy can be reported to a given url. You can set that url in the `report_uri` key of the `csp` config file. A great service that is specifically built for handling these violation reports is [http://report-uri.io/](http://report-uri.io/). 

#### Using multiple policies

To test changes to your CSP policy you can specify a second policy in the `report_only_policy` in the `csp` config key. The policy specified in `policy` will be enforced, the one in `report_only_policy` will not. This is great for testing a new policy or changes to existing CSP policy without breaking anything.

### Using whoops

Laravel comes with [whoops](https://github.com/filp/whoops), an error handling framework that helps you debug your application with a pretty visualization of exceptions. Whoops uses inline scripts and styles because it can't make any assumptions about the environment it is being used in, so it won't work unless you allow `unsafe-inline` for scripts and styles.

One approach to this problem is to check `config('app.debug')` when setting your policy. Unfortunately this bears the risk of forgetting to test your code with all CSP rules enabled and having your app break at deployment. Alternatively, you could allow `unsafe-inline` only on error pages by adding this to the `render` method of your exception handler (usually in `app/Exceptions/Handler.php`):
```php
$this->container->singleton(AppPolicy::class, function ($app) {
    return new AppPolicy();
});
app(AppPolicy::class)->addDirective(Directive::SCRIPT, Keyword::UNSAFE_INLINE);
app(AppPolicy::class)->addDirective(Directive::STYLE, Keyword::UNSAFE_INLINE);
```
where `AppPolicy` is the name of your CSP policy. This also works in every other situation to change the policy at runtime, in which case the singleton registration should be done in a service provider instead of the exception handler.

Note that `unsafe-inline` only works if you're not also sending a nonce or a `strict-dynamic` directive, so to be able to use this workaround, you have to specify all your inline scripts' and styles' hashes in the CSP header.

Another approach is to overwrite the `Spatie\Csp\Policies\Policy::shouldBeApplied()`-function in case Laravel responds with an error:

```php
namespace App\Services\Csp\Policies;

use Illuminate\Http\Request;
use Spatie\Csp;
use Symfony\Component\HttpFoundation\Response;

class MyCustomPolicy extends Csp\Policies\Policy
{
    public function configure()
    {
        // Add directives
    }
    
    public function shouldBeApplied(Request $request, Response $response): bool
    {
        if (config('app.debug') && ($response->isClientError() || $response->isServerError())) {
            return false;
        }

        return parent::shouldBeApplied($request, $response);
    }
}
```

This approach completely deactivates the CSP and therefore also works if a strict CSP is used.

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
- [Thomas Verhelst](https://github.com/TVke)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
