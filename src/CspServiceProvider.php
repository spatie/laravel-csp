<?php

namespace Spatie\Csp;

use Illuminate\Support\ServiceProvider;
use Spatie\Csp\Profiles\Profile\Profile;

class CspServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/csp.php' => config_path('csp.php'),
            ], 'config');
        }

        $this->app->bind(Profile::class, function() {
            $profileClass = config('csp.profile');

            $profile = app($profileClass);

            if (! empty(config('csp.report_uri'))) {
                $profile->reportTo(config('report_uri'));
            }

            if (config('csp.report_only')) {
                $profile->reportOnly();
            }

            return $profile;
        });
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/csp.php', 'csp');
    }
}
