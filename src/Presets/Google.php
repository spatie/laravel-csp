<?php

namespace Spatie\Csp\Presets;

use Spatie\Csp\Directive;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;

class Google implements Preset
{
    public function configure(Policy $policy): void
    {
        $policy
            ->add([Directive::SCRIPT, Directive::IMG, Directive::CONNECT], '*.googletagmanager.com')
            ->add([Directive::IMG, Directive::CONNECT], [
                '*.google-analytics.com',
                '*.analytics.google.com',
                '*.g.doubleclick.net',
                '*.google.com',
            ])
            ->addNonce(Directive::SCRIPT);
    }
}
