<?php

namespace Spatie\Csp\Presets;

use Spatie\Csp\Directive;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;

class Stripe implements Preset
{
    public function configure(Policy $policy): void
    {
        $policy
            ->add([Directive::FRAME, Directive::SCRIPT], 'https://connect-js.stripe.com')
            ->add([Directive::FRAME, Directive::SCRIPT], 'https://js.stripe.com')
            ->add(Directive::IMG, 'data:')
            ->add(Directive::IMG, 'https://*.stripe.com');
    }
}
