<?php

namespace Spatie\Csp\Presets;

use Spatie\Csp\Directive;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;

class Chargebee implements Preset
{
    public function configure(Policy $policy): void
    {
        $policy
            ->add(
                [Directive::FRAME, Directive::SCRIPT, Directive::SCRIPT_ELEM, Directive::STYLE_ELEM],
                ['js.chargebee.com']
            );
    }
}
