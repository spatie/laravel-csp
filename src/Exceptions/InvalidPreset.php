<?php

namespace Spatie\Csp\Exceptions;

use Exception;
use Spatie\Csp\Preset;

class InvalidPreset extends Exception
{
    public static function create(object $class): self
    {
        $className = $class::class;

        return new self("The CSP class `{$className}` is not valid. A valid policy implements ".Preset::class);
    }
}
