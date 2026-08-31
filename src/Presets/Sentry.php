<?php

namespace Spatie\Csp\Presets;

use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;
use Spatie\Csp\Scheme;

class Sentry implements Preset
{
    public function configure(Policy $policy): void
    {
        $policy
            ->add(Directive::CONNECT, [
                'https://*.ingest.de.sentry.io',
                'https://*.ingest.us.sentry.io',
            ])
            ->add(Directive::WORKER, [
                Keyword::SELF,
                Scheme::BLOB, // Session Replay and Browser Profiling spawn their compression worker from a blob URL.
            ]);
    }
}
