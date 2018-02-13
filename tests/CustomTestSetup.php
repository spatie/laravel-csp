<?php

namespace Spatie\LaravelCsp\Tests;

use Spatie\LaravelCsp\Profile\Csp;
use Spatie\LaravelCsp\Profile\CspInterface;

class CustomTestSetup extends Csp implements CspInterface
{
    /**
     * Fill this method with the $this->allows methods ||
     * add your own headers with $this->addHeader()
     */
    public function profileSetup()
    {
        $this->allowsGoogleAnalytics();
        $this->allowsGoogleFonts();
        $this->allowsYoutube();
    }
}
