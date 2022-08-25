<?php

namespace Spatie\Csp\Exceptions;

use Exception;

class MissingCspMetaTagPolicy extends Exception
{
    public static function create(): self
    {
        return new self("The [@cspMetaTag] directive expects to be passed a valid policy class name");
    }
}
