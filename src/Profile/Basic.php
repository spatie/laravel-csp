<?php

namespace Spatie\LaravelCsp\Profile;

class Basic extends Csp implements CspInterface
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
