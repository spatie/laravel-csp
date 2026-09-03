<?php

namespace Spatie\Csp\Presets;

use Spatie\Csp\Directive;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;

class CloudflareCdn implements Preset
{
    public function configure(Policy $policy): void
    {
        $policy
            ->add([Directive::STYLE, Directive::SCRIPT, Directive::FONT], [
                'https://cdnjs.cloudflare.com',
            ]);
    }
}
