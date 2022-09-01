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

        $this->callAfterResolving('view', function () {
            $this->registerBladeDirectives();
        });
    }

    private function registerBladeDirectives(): void
    {
        $bladeCompiler = $this->app->view->getEngineResolver()->resolve('blade')->getCompiler();

        $bladeCompiler->directive('nonce', function () {
            return '<?php echo "nonce=\"" . csp_nonce() . "\""; ?>';
        });

        $bladeCompiler->directive('cspMetaTag', function ($policyClass) {
            return "<?php echo csp_meta_tag({$policyClass}) ?>";
        });
    }
}
