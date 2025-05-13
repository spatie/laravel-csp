<?php

namespace Spatie\Csp\Presets;

use Spatie\Csp\Directive;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;

class MetaPixel implements Preset
{
    public function configure(Policy $policy): void
    {
        $policy
            ->add(Directive::SCRIPT, 'connect.facebook.net')
            ->add([Directive::FRAME, Directive::FORM_ACTION, Directive::IMG, Directive::CONNECT], 'www.facebook.com');
    }
}
