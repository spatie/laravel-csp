<?php

namespace Spatie\Csp\Presets;

use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;
use Spatie\Csp\Scheme;

class Posthog implements Preset
{
    public function configure(Policy $policy): void
    {
        $policy
            ->add(Directive::SCRIPT, '*.posthog.com')
            ->add([Directive::SCRIPT, Directive::SCRIPT_ELEM], ['eu-assets.i.posthog.com', 'us-assets.i.posthog.com'])
            ->add(Directive::CONNECT, ['eu.i.posthog.com', 'us.i.posthog.com']);
    }
}
