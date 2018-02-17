<?php

namespace Spatie\Csp\Profiles;

use Spatie\Csp\Directive;

class Basic extends Profile
{
    public function configure()
    {
        $this
            ->addDirective(Directive::CONNECT, "'self'")
            ->addDirective(Directive::DEFAULT, "'self'")
            ->addDirective(Directive::FORM_ACTION, "'self'")
            ->addDirective(Directive::IMG, "'self'")
            ->addDirective(Directive::MEDIA, "'self'")
            ->addDirective(Directive::SCRIPT, "'self'")
            ->addDirective(Directive::STYLE, "'self'")
            ->addDirective(Directive::STYLE, 'fonts.googleapis.com')
            ->addDirective(Directive::FONT, 'fonts.gstatic.com')
            ->addNonceForDirective(Directive::SCRIPT)
            ->addNonceForDirective(Directive::STYLE);
    }
}
