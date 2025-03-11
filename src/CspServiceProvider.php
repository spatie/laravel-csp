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
        if (config('csp.nonce_enabled', true)) {
            $this->app->singleton(NonceGenerator::class, config('csp.nonce_generator'));

            $this->app->singleton('csp-nonce', function () {
                return app(NonceGenerator::class)->generate();
            });
        }

        $this->callAfterResolving('view', function () {
            $this->registerBladeDirectives();
        });
    }

    private function registerBladeDirectives(): void
    {
        $bladeCompiler = $this->app->make('view.engine.resolver')->resolve('blade')->getCompiler();

        $bladeCompiler->directive('cspNonce', function () {
            return '<?php echo "nonce=\"" . app(\'csp-nonce\') . "\""; ?>';
        });

        $bladeCompiler->directive('cspMetaTag', function ($policyClass) {
            return "<?php echo \Spatie\Csp\CspMetaTag::create({$policyClass}) ?>";
        });

        $bladeCompiler->directive('cspMetaTagReportOnly', function ($policyClass) {
            return "<?php echo \Spatie\Csp\CspMetaTag::createReportOnly({$policyClass}) ?>";
        });
    }
}
