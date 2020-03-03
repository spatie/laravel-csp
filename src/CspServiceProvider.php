<?php

namespace Spatie\Csp;

use Illuminate\Support\ServiceProvider;
use Spatie\Csp\Nonce\NonceGenerator;

class CspServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole() && function_exists('config_path')) {
            $this->publishes([
                __DIR__.'/../config/csp.php' => config_path('csp.php'),
            ], 'config');
        }

        $this->app->singleton(NonceGenerator::class, config('csp.nonce_generator'));

        $this->app->singleton('csp-nonce', function () {
            return app(NonceGenerator::class)->generate();
        });

        $this->app->view->getEngineResolver()->resolve('blade')->getCompiler()->directive('nonce', function () {
            return '<?php echo "nonce=\"" . csp_nonce() . "\""; ?>';
        });
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/csp.php', 'csp');
    }
}
