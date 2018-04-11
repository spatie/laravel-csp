<?php

namespace Spatie\Csp;

use ReflectionClass;

abstract class Directive
{
    const BASE = 'base-uri';
    const BLOCK_ALL_MIXED_CONTENT = 'block-all-mixed-content';
    const CHILD = 'child-src';
    const CONNECT = 'connect-src';
    const DEFAULT = 'default-src';
    const FONT = 'font-src';
    const FORM_ACTION = 'form-action';
    const FRAME = 'frame-src';
    const FRAME_ANCESTORS = 'frame-ancestors';
    const IMG = 'img-src';
    const MANIFEST = 'manifest-src';
    const MEDIA = 'media-src';
    const OBJECT = 'object-src';
    const PLUGIN = 'plugin-types';
    const REPORT = 'report-uri';
    const SANDBOX = 'sandbox';
    const SCRIPT = 'script-src';
    const STYLE = 'style-src';
    const UPGRADE_INSECURE_REQUESTS = 'upgrade-insecure-requests';
    const WORKER = 'worker-src';

    const VALUE_NONE = 'none';
    const VALUE_REPORT_SAMPLE = 'report-sample';
    const VALUE_SELF = 'self';
    const VALUE_STRICT_DYNAMIC = 'strict-dynamic';
    const VALUE_UNSAFE_EVAL = 'unsafe-eval';
    const VALUE_UNSAFE_INLINE = 'unsafe-inline';

    public static function isValid(string $directive): bool
    {
        $constants = (new ReflectionClass(static::class))->getConstants();

        return in_array($directive, $constants);
    }
}
