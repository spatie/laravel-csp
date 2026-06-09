<?php

namespace Spatie\Csp\Presets;

use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;

class ThereThere implements Preset
{
    // Allows embedding the There There support bubble widget (https://there-there.app).
    // The loader script is served from files.there-there.app and injects an iframe,
    // its API/WebSocket traffic, and a small <style> block for the launcher animations.
    public function configure(Policy $policy): void
    {
        $policy
            ->add(Directive::SCRIPT, 'https://files.there-there.app')
            ->add(Directive::FRAME, 'https://there-there.app')
            ->add(Directive::CONNECT, [
                'https://there-there.app',
                'wss://there-there.app',
            ])
            ->add(Directive::STYLE, Keyword::UNSAFE_INLINE);
    }
}
