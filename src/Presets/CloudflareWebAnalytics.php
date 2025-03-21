<?php

namespace Spatie\Csp\Presets;

use Spatie\Csp\Directive;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;

class CloudflareWebAnalytics implements Preset
{
    public function configure(Policy $policy): void
    {
        $policy
            ->add(Directive::CONNECT, 'cloudflareinsights.com')
            ->add(Directive::SCRIPT, 'static.cloudflareinsights.com');
    }
}
