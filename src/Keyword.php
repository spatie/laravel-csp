<?php

namespace Spatie\Csp;

abstract class Keyword
{
    const NONE = 'none';
    const NO_VALUE = false;
    const REPORT_SAMPLE = 'report-sample';
    const SELF = 'self';
    const STRICT_DYNAMIC = 'strict-dynamic';
    const UNSAFE_EVAL = 'unsafe-eval';
    const UNSAFE_INLINE = 'unsafe-inline';
}
