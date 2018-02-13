<?php

namespace Spatie\LaravelCsp\Profile;

interface CspInterface
{
    /**
     * Fill this method with the $this->allows methods ||
     * add your own headers with $this->addHeader()
     */
    public function profileSetup();
}
