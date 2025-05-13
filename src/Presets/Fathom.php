<?php

namespace Spatie\Csp\Presets;

use Spatie\Csp\Directive;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;

class Fathom implements Preset
{
    public function configure(Policy $policy): void
    {
        $policy
            ->add([Directive::IMG, Directive::SCRIPT, Directive::CONNECT], 'cdn.usefathom.com');
    }
}
