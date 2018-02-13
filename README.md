# Set up the Content Security Policy header with ease.

Safety on the web is an ever growing topic and the `Content-Security-Policy` header is a way to block outsiders from getting on your site.
This package is not a one-size-fit-all solution, but it makes the setup easier by having a few common setups: 

- `strict`: A strict setup where you have no outside dependencies.
- `custom`: Of course you can create your own setups. (see [Usage](https://github.com/spatie/laravel-csp#usage))


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

    'enabled' => env('CSP', true),
    
    'csp_profile' => \Spatie\LaravelCsp\Profile\Basic::class,

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

You can create your custom CSP setup very easy by declaring your `CustomCsp` class that extends the `Csp` class and implements the `CspInterface` like this:
You can set any header with:

```php
class CustomCsp extends Csp implements CspInterface
{
    /**
     * Fill this method with the $this->allows methods ||
     * add your own headers with $this->addHeader()
     */
    public function profileSetup()
    {
        $this->allowsGoogleAnalytics();
        $this->allowsGoogleFonts();
        $this->allowsYoutube();
    }
}
``` 

You can disable the `CSP` in the `.env` file, this will disable the CSP header.

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
