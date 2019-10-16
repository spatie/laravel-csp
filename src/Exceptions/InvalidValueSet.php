<?php

namespace Spatie\Csp\Exceptions;

use Exception;

class InvalidValueSet extends Exception
{
    public static function noneMustBeOnlyValue(): self
    {
        return new self('The keyword none can only be used on its own');
    }
}
