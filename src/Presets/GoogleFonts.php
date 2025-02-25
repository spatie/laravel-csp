<?php

namespace Spatie\Csp\Presets;

use Spatie\Csp\Directive;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;

class GoogleFonts implements Preset
{
    public function configure(Policy $policy): void
    {
        $policy
            ->add(Directive::STYLE, 'fonts.googleapis.com')
            ->add(Directive::FONT, 'fonts.gstatic.com');
    }
}
