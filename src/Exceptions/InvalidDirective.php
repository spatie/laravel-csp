<?php

namespace Spatie\LaravelCsp\Exceptions;

use Exception;

class InvalidDirective extends Exception
{
    public static function notSupported(string $directive): self
    {
        return new self("The directive `{$directive}` is a invalid directive in the CSP header or is not enough supported by browsers. ");
    }

    public static function inline(string $directive): self
    {
        return new self("The directive `{$directive}` is a invalid directive to use nonce or hash with in the CSP header");
    }
}
