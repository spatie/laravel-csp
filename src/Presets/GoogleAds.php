<?php

namespace Spatie\Csp\Presets;

use Spatie\Csp\Directive;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;

class GoogleAds implements Preset
{
    public function configure(Policy $policy): void
    {
        $policy
            ->add([Directive::CONNECT, Directive::IMG, Directive::SCRIPT], [
                'www.googleadservices.com',
                'pagead2.googlesyndication.com',
                'googleads.g.doubleclick.net',
                'www.google.com',
            ])
            ->add([Directive::IMG, Directive::SCRIPT], 'www.googletagmanager.com')
            ->add([Directive::CONNECT, Directive::IMG], 'google.com')
            ->add([Directive::CONNECT], 'ad.doubleclick.net')
            ->add([Directive::FRAME], 'www.googletagmanager.com')
            ->addNonce(Directive::SCRIPT);
    }
}
