<?php

namespace Spatie\Csp\Presets;

use Spatie\Csp\Directive;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;

class HotJar implements Preset
{
    // @see: https://help.hotjar.com/hc/en-us/articles/115011640307-Content-Security-Policies
    // @see: https://help.hotjar.com/hc/en-us/articles/46691014447761-Moving-from-Hotjar-to-Contentsquare-FAQs
    public function configure(Policy $policy): void
    {
        $policy
            ->add([Directive::FRAME, Directive::SCRIPT, Directive::FONT, Directive::STYLE, Directive::IMG], '*.hotjar.com')
            ->add([Directive::SCRIPT, Directive::IMG], 'https://*.hotjar.io')
            ->add(Directive::CONNECT, ['https://*.hotjar.com', 'https://*.hotjar.io', 'wss://*.hotjar.com']);
    }
}
