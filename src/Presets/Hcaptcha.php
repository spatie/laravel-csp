<?php

namespace Spatie\Csp\Presets;

use Spatie\Csp\Directive;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;

class Hcaptcha implements Preset
{
    public function configure(Policy $policy): void
    {
        $policy
            ->add([Directive::SCRIPT, Directive::FRAME, Directive::STYLE, Directive::CONNECT], ['hcaptcha.com', '*.hcaptcha.com']);
    }
}
