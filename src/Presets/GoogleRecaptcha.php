<?php

namespace Spatie\Csp\Presets;

use Spatie\Csp\Directive;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;

class GoogleRecaptcha implements Preset
{
    public function configure(Policy $policy): void
    {
        $policy
            ->add(Directive::SCRIPT, ['www.google.com/recaptcha/', 'www.gstatic.com/recaptcha/'])
            ->add(Directive::FRAME, ['www.google.com/recaptcha/', 'recaptcha.google.com/recaptcha/']);
    }
}
