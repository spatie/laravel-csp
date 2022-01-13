<?php

namespace Spatie\Csp;

use Spatie\Csp\Nonce\NonceGenerator;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class CspServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-csp')
            ->hasConfigFile();
    }

    public function packageBooted()
    {
        $this->app->singleton(NonceGenerator::class, config('csp.nonce_generator'));

        $this->app->singleton('csp-nonce', function () {
            return app(NonceGenerator::class)->generate();
        });

        $this->app->view->getEngineResolver()->resolve('blade')->getCompiler()->directive('nonce', function () {
            return '<?php echo "nonce=\"" . csp_nonce() . "\""; ?>';
        });
    }
}
