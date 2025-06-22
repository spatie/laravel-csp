<?php

namespace Spatie\Csp;

enum Keyword: string
{
    case NONE = 'none';
    case REPORT_SAMPLE = 'report-sample';
    case SCRIPT = 'script';
    case SELF = 'self';
    case STRICT_DYNAMIC = 'strict-dynamic';
    case UNSAFE_EVAL = 'unsafe-eval';
    case UNSAFE_HASHES = 'unsafe-hashes';
    case UNSAFE_INLINE = 'unsafe-inline';
    case UNSAFE_WEB_ASSEMBLY_EXECUTION = 'wasm-unsafe-eval';
}
