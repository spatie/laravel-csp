<?php

namespace Spatie\Csp\Exceptions;

use Exception;
use Spatie\Csp\Profiles\Profile\Profile;

class InvalidCspProfile extends Exception
{
    public static function create($class): self
    {
        $className = get_class($class);

        return new self("The csp profile `{$className}` is not valid. A valid profile extends ".Profile::class);
    }
}
