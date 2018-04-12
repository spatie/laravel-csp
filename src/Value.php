<?php

namespace Spatie\Csp;

abstract class Value
{
    const NONE = 'none';
    const REPORT_SAMPLE = 'report-sample';
    const SELF = 'self';
    const STRICT_DYNAMIC = 'strict-dynamic';
    const UNSAFE_EVAL = 'unsafe-eval';
    const UNSAFE_INLINE = 'unsafe-inline';
}
