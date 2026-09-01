<?php

namespace Spatie\Csp\Presets;

use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;
use Spatie\Csp\Scheme;

class Sentry implements Preset
{
    // @see: https://docs.sentry.io/platforms/javascript/session-replay/
    // Session Replay compresses payloads in a worker created from a blob URL. `child-src` covers
    // Safari 15.4 and older, which predate `worker-src`. Both list `'self'` because neither
    // directive falls back to `default-src` once it is present.
    public function configure(Policy $policy): void
    {
        $policy
            ->add(Directive::CONNECT, [
                'https://*.ingest.de.sentry.io',
                'https://*.ingest.us.sentry.io',
            ])
            ->add([Directive::WORKER, Directive::CHILD], [
                Keyword::SELF,
                Scheme::BLOB,
            ]);
    }
}
