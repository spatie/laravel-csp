<?php

namespace Spatie\Csp;

use Spatie\Csp\Nonce\NonceGenerator;
use Illuminate\Support\Facades\Blade;
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

        $this->app->singleton(NonceGenerator::class, config('csp.nonce_generator'));

        $this->app->singleton('csp-nonce', function () {
            return app(NonceGenerator::class)->generate();
        });

        Blade::directive('nonce', function () {
            $nonce = cspNonce();
            return "<?php echo nonce='{$nonce}' ?>";
        });
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/csp.php', 'csp');
    }
}
