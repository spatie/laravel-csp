<?php

namespace Spatie\Csp\Exceptions;

use Exception;
use Spatie\Csp\Profiles\Profile\Profile;

class InvalidCspProfile extends Exception
{
    public static function create(string $class): self
    {
        return new self("The csp profile `{$class}` is not valid. A valid profile extends " . Profile::class);
    }
}
