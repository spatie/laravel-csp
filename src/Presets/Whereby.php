<?php

namespace Spatie\Csp\Presets;

use Spatie\Csp\Directive;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;

class Whereby implements Preset
{
    public function configure(Policy $policy): void
    {
        $policy
            ->add([Directive::SCRIPT, Directive::FRAME], [
                'https://*.whereby.com',
            ]);
    }
}
