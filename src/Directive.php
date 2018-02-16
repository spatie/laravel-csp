<?php

namespace Spatie\Csp;

abstract class Directive
{
    const BASE = 'base-uri';
    const BLOCK_ALL_MIXED_CONTENT = 'block-all-mixed-content';
    const CHILD = 'child-src';
    const CONNECT = 'connect-src';
    const DEFAULT = 'default-src';
    const FONT = 'font-src';
    const FORM = 'form-action';
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

    public static function isValid(string $directive): bool
    {
        $constants = (new ReflectionClass(static::class))->getConstants();

        return in_array($directive, $constants);
    }
}
