<?php

namespace Spatie\Csp\Presets;

use Spatie\Csp\Directive;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;

class JsDelivr implements Preset
{

    public function configure(Policy $policy): void
    {
        $policy
            ->add([Directive::SCRIPT, Directive::STYLE, Directive::FONT], 'cdn.jsdelivr.net');
    }
}
