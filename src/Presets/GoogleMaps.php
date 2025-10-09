<?php

namespace Spatie\Csp\Presets;

use Spatie\Csp\Directive;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;

class GoogleMaps implements Preset
{
    public function configure(Policy $policy): void
    {
        $policy->add(Directive::SCRIPT, [
            'https://*.googleapis.com',
            'https://*.gstatic.com',
            '*.google.com',
            'https://*.ggpht.com',
            '*.googleusercontent.com',
            'blob:',
        ]);
        $policy->add(Directive::IMG, [
            'https://*.googleapis.com',
            'https://*.gstatic.com',
            '*.google.com',
            '*.googleusercontent.com',
            'data:',
        ]);
        $policy->add(Directive::FRAME, '*.google.com');
        $policy->add(Directive::CONNECT, [
            'https://*.googleapis.com',
            '*.google.com',
            'https://*.gstatic.com',
            'data:',
            'blob:',
        ]);
        $policy->add(Directive::FONT, 'https://fonts.gstatic.com');
        $policy->add(Directive::STYLE, 'https://fonts.googleapis.com');
        $policy->add(Directive::WORKER, 'blob:');
    }
}
