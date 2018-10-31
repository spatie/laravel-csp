<?php

namespace Spatie\Csp;

abstract class Value
{
    const DATA = 'data:';
    const NONE = 'none';
    const NO_VALUE = '';
    const REPORT_SAMPLE = 'report-sample';
    const SELF = 'self';
    const STRICT_DYNAMIC = 'strict-dynamic';
    const UNSAFE_EVAL = 'unsafe-eval';
    const UNSAFE_INLINE = 'unsafe-inline';
}
