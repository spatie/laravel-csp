<?php

namespace Spatie\Csp\Profiles;

trait Allows
{
    protected function allowsBasics(): self
    {
        return $this
            ->addHeader(Directive::DEFAULT, 'none')
            ->addHeader(Directive::CONNECT, 'self')
            ->addHeader(Directive::FORM, 'self')
            ->addHeader(Directive::IMG, 'self')
            ->addHeader(Directive::SCRIPT, 'self')
            ->addHeader(Directive::STYLE, 'self')
            ->addHeader(Directive::MEDIA, 'self');
    }

    public function allowsGoogleAnalytics(): self
    {
        return $this
            ->addHeader(Directive::CONNECT, 'https://www.google-analytics.com')
            ->addHeader(Directive::SCRIPT, 'https://www.google-analytics.com')
            ->addHeader(Directive::SCRIPT, 'https://www.googletagmanager.com');
    }

    public function allowsBase64Fonts(): self
    {
        return $this->addHeader(Directive::FONT, 'data:');
    }

    public function allowsGoogleFonts()
    {
        $this
            ->addHeader(Directive::FONT, 'https://fonts.gstatic.com')
            ->addHeader(Directive::STYLE, 'https://fonts.googleapis.com');
    }

    public function allowsFontAwesomeFonts()
    {
        $this
            ->addHeader(Directive::FONT, 'https://use.fontawesome.com')
            ->addHeader(Directive::STYLE, 'https://use.fontawesome.com');
    }

    public function allowsYoutube()
    {
        $this
            ->addHeader(Directive::CHILD, 'https://www.youtube.com')
            ->addHeader(Directive::FRAME, 'https://www.youtube.com')
            ->addHeader(Directive::WORKER, 'https://www.youtube.com');
    }

    public function allowsPusher()
    {
        $this
            ->addHeader(Directive::CONNECT, 'https://*.pusher.com')
            ->addHeader(Directive::SCRIPT, 'https://stats.pusher.com');
    }

    public function allowsPdfs()
    {
        $this->addHeader(Directive::PLUGIN, 'https://application/pdf');
    }

    public function allowsJavaApplets()
    {
        $this->addHeader(Directive::CHILD, 'https://application/x-java-applet');
    }

    public function allowsGoogleApi()
    {
        $this->addHeader(Directive::CONNECT, 'https://ajax.googleapis.com');
    }

    public function allowsYahooApi()
    {
        $this->addHeader(Directive::CONNECT, 'https://query.yahooapis.com');
    }
}
