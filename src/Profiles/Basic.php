<?php

namespace Spatie\Csp\Profiles;

use Spatie\Csp\Directive;

class Basic extends Profile
{
    public function registerDirectives()
    {
        $this
            ->addDirective(Directive::CONNECT, "'self'")
            ->addDirective(Directive::DEFAULT, "'self'")
            ->addDirective(Directive::FORM, "'self'")
            ->addDirective(Directive::IMG, "'self'")
            ->addDirective(Directive::MEDIA, "'self'")
            ->addDirective(Directive::SCRIPT, "'self'")
            ->addDirective(Directive::STYLE, "'self'");
    }
}