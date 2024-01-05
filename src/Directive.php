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
    const PREFETCH = 'prefetch-src';
    const REPORT = 'report-uri';
    const REPORT_TO = 'report-to';
    const REQUIRE_TRUSTED_TYPES_FOR = 'require-trusted-types-for';
    const SANDBOX = 'sandbox';
    const SCRIPT = 'script-src';
    const SCRIPT_ATTR = 'script-src-attr';
    const SCRIPT_ELEM = 'script-src-elem';
    const STYLE = 'style-src';
    const STYLE_ATTR = 'style-src-attr';
    const STYLE_ELEM = 'style-src-elem';
    const UPGRADE_INSECURE_REQUESTS = 'upgrade-insecure-requests';
    const WEB_RTC = 'webrtc-src';
    const WORKER = 'worker-src';

    public static function isValid(string $directive): bool
    {
        $constants = (new ReflectionClass(static::class))->getConstants();

        return in_array($directive, $constants);
    }
}
