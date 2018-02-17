<?php

namespace Spatie\Csp;

use Illuminate\Support\ServiceProvider;

class CspServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/csp.php' => config_path('csp.php'),
            ], 'config');
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/csp.php', 'csp');
    }
}
