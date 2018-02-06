<?php

namespace Spatie\LaravelCsp;

use Illuminate\Support\ServiceProvider;

class LaravelCspServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/csp.php' => config_path('csp.php'),
            ], 'config');

            /*
            $this->loadViewsFrom(__DIR__.'/../resources/views', 'LaravelCsp');

            $this->publishes([
                __DIR__.'/../resources/views' => base_path('resources/views/vendor/LaravelCsp'),
            ], 'views');
            */
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/csp.php', 'csp');
    }
}
