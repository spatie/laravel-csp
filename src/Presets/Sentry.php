<?php

namespace Spatie\Csp\Presets;

use Spatie\Csp\Directive;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;

class Sentry implements Preset
{
    public function configure(Policy $policy): void
    {
        $policy
            ->add(Directive::CONNECT, [
                'https://*.ingest.de.sentry.io',
                'https://*.ingest.us.sentry.io',
            ]);
    }
}
