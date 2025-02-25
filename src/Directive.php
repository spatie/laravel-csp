<?php

namespace Spatie\Csp;

enum Directive: string
{
    case BASE = 'base-uri';
    case BLOCK_ALL_MIXED_CONTENT = 'block-all-mixed-content';
    case CHILD = 'child-src';
    case CONNECT = 'connect-src';
    case DEFAULT = 'default-src';
    case FONT = 'font-src';
    case FORM_ACTION = 'form-action';
    case FRAME = 'frame-src';
    case FRAME_ANCESTORS = 'frame-ancestors';
    case IMG = 'img-src';
    case MANIFEST = 'manifest-src';
    case MEDIA = 'media-src';
    case OBJECT = 'object-src';
    case PLUGIN = 'plugin-types';
    case PREFETCH = 'prefetch-src';
    case REPORT = 'report-uri';
    case REPORT_TO = 'report-to';
    case REQUIRE_TRUSTED_TYPES_FOR = 'require-trusted-types-for';
    case SANDBOX = 'sandbox';
    case SCRIPT = 'script-src';
    case SCRIPT_ATTR = 'script-src-attr';
    case SCRIPT_ELEM = 'script-src-elem';
    case STYLE = 'style-src';
    case STYLE_ATTR = 'style-src-attr';
    case STYLE_ELEM = 'style-src-elem';
    case UPGRADE_INSECURE_REQUESTS = 'upgrade-insecure-requests';
    case WEB_RTC = 'webrtc-src';
    case WORKER = 'worker-src';
}
