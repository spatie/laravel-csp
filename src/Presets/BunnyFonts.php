<?php

namespace Spatie\Csp\Presets;

use Spatie\Csp\Directive;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;

class BunnyFonts implements Preset
{
    public function configure(Policy $policy): void
    {
        $policy
            ->add(Directive::STYLE, 'fonts.bunny.net')
            ->add(Directive::FONT, 'fonts.bunny.net');
    }
}
