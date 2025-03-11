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
            ->add([Directive::CONNECT, Directive::IMG, Directive::SCRIPT], [
                '*.googletagmanager.com',
            ])
            ->addNonce(Directive::SCRIPT);
    }
}
