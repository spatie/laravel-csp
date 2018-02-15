<?php

namespace Spatie\LaravelCsp\Profiles;

use Illuminate\Support\Collection;

abstract class Directive
{
    // Details at: https://www.w3.org/TR/CSP3/#csp-directives

    const base = 'base-uri';

    const child = 'child-src';

    const connect = 'connect-src';

    const default = 'default-src';

    const font = 'font-src';

    const form = 'form-action';

    const frame = 'frame-src';

    const frameAncestors = 'frame-ancestors';

    const img = 'img-src';

    const manifest = 'manifest-src';

    const media = 'media-src';

    const mixed = 'block-all-mixed-content';

    const object = 'object-src';

    const plugin = 'plugin-types';

    const report = 'report-uri';

    const sandbox = 'sandbox';

    const script = 'script-src';

    const style = 'style-src';

    const upgrade = 'upgrade-insecure-requests';

    const worker = 'worker-src';

    public static function all(): Collection
    {
        return collect([
            self::base,
            self::child,
            self::connect,
            self::default,
            self::font,
            self::form,
            self::frame,
            self::frameAncestors,
            self::img,
            self::manifest,
            self::media,
            self::mixed,
            self::object,
            self::plugin,
            self::report,
            self::sandbox,
            self::script,
            self::style,
            self::upgrade,
            self::worker,
        ]);
    }
}
