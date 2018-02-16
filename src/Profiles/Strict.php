<?php

namespace Spatie\LaravelCsp\Profiles;

use Spatie\LaravelCsp\Csp;

class Strict extends Csp implements CspInterface
{
    /**
     * Fill this method with the $this->allows methods ||
     * add your own headers with $this->addHeader().
     */
    public function profileSetup()
    {
        $this->allowsInlineScript();
    }
}
