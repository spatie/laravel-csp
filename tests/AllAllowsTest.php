<?php

namespace Spatie\LaravelCsp\Tests;

use Spatie\LaravelCsp\Profiles\Csp;
use Spatie\LaravelCsp\Profiles\CspInterface;

class AllAllowsTest extends Csp implements CspInterface
{
    /**
     * Fill this method with the $this->allows methods ||
     * add your own headers with $this->addHeader()
     */
    public function profileSetup()
    {
        $this->allowsGoogleAnalytics();
        $this->allowsBase64Fonts();
        $this->allowsGoogleFonts();
        $this->allowsFontAwesomeFonts();
        $this->allowsYoutube();
        $this->allowsCodepen();
        $this->allowsPusher();
        $this->allowsPdfs();
        $this->allowsJavaApplets();
        $this->allowsGoogleApi();
        $this->allowsYahooApi();
    }
}
