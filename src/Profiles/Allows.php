<?php

namespace Spatie\LaravelCsp\Profiles;

trait Allows
{
    protected function allowsBasics()
    {
        $this
            ->addHeader(Directive::default, 'none')
            ->addHeader(Directive::connect, 'self')
            ->addHeader(Directive::form, 'self')
            ->addHeader(Directive::img, 'self')
            ->addHeader(Directive::script, 'self')
            ->addHeader(Directive::style, 'self')
            ->addHeader(Directive::media, 'self');
    }

    public function allowsGoogleAnalytics()
    {
        $this
            ->addHeader(Directive::connect, 'https://www.google-analytics.com')
            ->addHeader(Directive::script, 'https://www.google-analytics.com')
            ->addHeader(Directive::script, 'https://www.googletagmanager.com');
    }

    public function allowsBase64Fonts()
    {
        $this->addHeader(Directive::font, 'data:');
    }

    public function allowsGoogleFonts()
    {
        $this
            ->addHeader(Directive::font, 'https://fonts.gstatic.com')
            ->addHeader(Directive::style, 'https://fonts.googleapis.com');
    }

    public function allowsFontAwesomeFonts()
    {
        $this
            ->addHeader(Directive::font, 'https://use.fontawesome.com')
            ->addHeader(Directive::style, 'https://use.fontawesome.com');
    }

    public function allowsYoutube()
    {
        $this
            ->addHeader(Directive::child, 'https://www.youtube.com')
            ->addHeader(Directive::frame, 'https://www.youtube.com')
            ->addHeader(Directive::worker, 'https://www.youtube.com');
    }

    public function allowsCodepen()
    {
        $this
            ->addHeader(Directive::child, 'https://codepen.io')
            ->addHeader(Directive::frame, 'https://codepen.io')
            ->addHeader(Directive::worker, 'https://codepen.io');
    }

    public function allowsPusher()
    {
        $this
            ->addHeader(Directive::connect, 'https://*.pusher.com')
            ->addHeader(Directive::script, 'https://stats.pusher.com');
    }

    public function allowsPdfs()
    {
        $this->addHeader(Directive::plugin, 'https://application/pdf');
    }

    public function allowsJavaApplets()
    {
        $this->addHeader(Directive::child, 'https://application/x-java-applet');
    }

    public function allowsGoogleApi()
    {
        $this->addHeader(Directive::connect, 'https://ajax.googleapis.com');
    }

    public function allowsYahooApi()
    {
        $this->addHeader(Directive::connect, 'https://query.yahooapis.com');
    }

    public function allowsInlineScript(int $amount = 1)
    {
        for ($i = 0; $i < $amount; ++$i) {
            $nonce = $this->createScriptNonce();

            $this->addHeader(Directive::script, "nonce-{$nonce}");
        }
    }

    public function allowsInlineStyle(int $amount = 1)
    {
        for ($i = 0; $i < $amount; ++$i) {
            $nonce = $this->createScriptNonce();

            $this->addHeader(Directive::style, "nonce-{$nonce}");
        }
    }
}
