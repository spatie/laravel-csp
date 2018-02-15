<?php

namespace Spatie\LaravelCsp\Exceptions;

use Exception;

class InvalidCspProfileClass extends Exception
{
    public static function create(string $class): self
    {
        return new self("The class `{$class}` does not extends the Spatie\LaravelCsp\Profiles\Csp class or implements the Spatie\LaravelCsp\Profiles\CspInterface. ");
    }
}
