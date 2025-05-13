<?php

namespace Spatie\Csp\Presets;

use Spatie\Csp\Directive;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;

class GoogleAnalytics implements Preset
{
    public function configure(Policy $policy): void
    {
        $policy
            ->add([Directive::CONNECT, Directive::IMG, Directive::SCRIPT], [
                '*.google-analytics.com',
                '*.analytics.google.com',
                '*.g.doubleclick.net',
                '*.google.com',
                'pagead2.googlesyndication.com',
            ])
            ->add([Directive::FRAME], 'td.doubleclick.net')
            ->addNonce(Directive::SCRIPT);
    }
}
