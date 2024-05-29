<?php

namespace Spatie\Csp;

abstract class Keyword
{
    const NONE = 'none';
    const REPORT_SAMPLE = 'report-sample';
    const SELF = 'self';
    const STRICT_DYNAMIC = 'strict-dynamic';
    const UNSAFE_EVAL = 'unsafe-eval';
    const UNSAFE_HASHES = 'unsafe-hashes';
    const UNSAFE_INLINE = 'unsafe-inline';
    const UNSAFE_WEB_ASSEMBLY_EXECUTION = 'wasm-unsafe-eval';
}
