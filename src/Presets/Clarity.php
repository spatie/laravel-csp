<?php

namespace Spatie\Csp\Presets;

use Spatie\Csp\Directive;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;

class Clarity implements Preset
{
    public function configure(Policy $policy): void
    {
        $policy
            ->add([Directive::CONNECT, Directive::SCRIPT, Directive::IMG], 'https://*.clarity.ms')
            ->add([Directive::CONNECT, Directive::SCRIPT, Directive::IMG], 'https://c.bing.com');
    }
}
