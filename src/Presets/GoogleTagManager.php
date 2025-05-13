<?php

namespace Spatie\Csp\Presets;

use Spatie\Csp\Directive;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;

class GoogleTagManager implements Preset
{
    public function configure(Policy $policy): void
    {
        $policy
            ->add([Directive::CONNECT, Directive::IMG, Directive::FRAME, Directive::SCRIPT], [
                '*.googletagmanager.com',
                '*.googleadservices.com',
            ])
            ->addNonce(Directive::SCRIPT);
    }
}
