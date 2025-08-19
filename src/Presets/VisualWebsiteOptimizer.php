<?php

namespace Spatie\Csp\Presets;

use Spatie\Csp\Directive;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;

class VisualWebsiteOptimizer implements Preset
{
    public function configure(Policy $policy): void
    {
        $policy
            ->add([Directive::SCRIPT, Directive::IMG, Directive::CONNECT], [
                'blob:',
                'https://dev.visualwebsiteoptimizer.com',
            ]);
    }
}
