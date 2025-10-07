<?php

namespace Spatie\Csp\Presets;

use Spatie\Csp\Directive;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;

/**
 * Content Security Policy (CSP)
 * for spatie/laravel-csp
 *
 * @see https://github.com/spatie/laravel-csp
 */
class TrackJs implements Preset
{
    public function configure(Policy $policy): void
    {
        $policy
            ->add(Directive::CONNECT, [
                'capture.trackjs.com',
            ])
            ->add(Directive::IMG, [
                'usage.trackjs.com',
            ])
            ->add(Directive::SCRIPT, [
                'cdn.trackjs.com',
            ])
            ->add(Directive::SCRIPT_ELEM, [
                'cdn.trackjs.com',
                'js.trackjs.com',
                'cdn.trackjs.com',
            ]);
    }
}
