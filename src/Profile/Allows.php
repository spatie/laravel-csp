<?php

namespace Spatie\LaravelCsp\Profile;

trait Allows
{
    // basic
    public function allowsBasics()
    {
        $this->addHeader(Directive::default, 'none')
            ->addHeader(Directive::connect, 'self')
            ->addHeader(Directive::form, 'self')
            ->addHeader(Directive::img, 'self')
            ->addHeader(Directive::script, 'self')
            ->addHeader(Directive::style, 'self')
            ->addHeader(Directive::media, 'self');
    }

    /**
     * analytics
     *
     * google analytics by default uses the image approach, this contains some risks,
     * use google analytics XHR of beacon approach for your users safety.
     * how-to implement: https://developers.google.com/analytics/devguides/collection/gtagjs/sending-data#specify_different_transport_mechanisms
     */
    // analytics
    public function allowsGoogleAnalytics()
    {
        $this->addHeader(Directive::connect, 'www.google-analytics.com')
            ->addHeader(Directive::script, 'www.google-analytics.com')
            ->addHeader(Directive::script, 'www.googletagmanager.com');
    }

    // fonts
    public function allowsBase64Fonts()
    {
        $this->addHeader(Directive::font, 'data:');
    }

    public function allowsGoogleFonts()
    {
        $this->addHeader(Directive::font, 'fonts.gstatic.com')
            ->addHeader(Directive::style, 'fonts.googleapis.com');
    }

    public function allowsFontAwesomeFonts()
    {
        $this->addHeader(Directive::font, 'use.fontawesome.com')
            ->addHeader(Directive::style, 'use.fontawesome.com');
    }

    // embeds
    public function allowsYoutube()
    {
        $this->addHeader(Directive::child, 'www.youtube.com')
            ->addHeader(Directive::frame, 'www.youtube.com')
            ->addHeader(Directive::worker, 'www.youtube.com');
    }

    public function allowsCodepen()
    {
        $this->addHeader(Directive::child, 'codepen.io')
            ->addHeader(Directive::frame, 'codepen.io')
            ->addHeader(Directive::worker, 'codepen.io');
    }

    // web sockets
    public function allowsPusher()
    {
        $this->addHeader(Directive::connect, '*.pusher.com')
            ->addHeader(Directive::script, 'stats.pusher.com');
    }

    // plugins
    public function allowsPdfs()
    {
        $this->addHeader(Directive::plugin, 'application/pdf');
    }

    public function allowsJavaApplets()
    {
        $this->addHeader(Directive::child, 'application/x-java-applet');
    }

    /**
     * CDNs (Warning) try avoiding CDNs in combination with CSP
     * google API: https://github.com/cure53/XSSChallengeWiki/wiki/H5SC-Minichallenge-3:-%22Sh*t,-it%27s-CSP!%22#submissions
     */
    // CDNs
    public function allowsGoogleApi()
    {
        $this->addHeader(Directive::connect, 'ajax.googleapis.com');
    }

    public function allowsYahooApi()
    {
        $this->addHeader(Directive::connect, 'query.yahooapis.com');
    }
}
