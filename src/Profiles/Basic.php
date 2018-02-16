<?php

namespace Spatie\Csp\Profiles\Profile;

class Basic extends Profile
{
    public function registerDirectives()
    {
        $this
            ->addDirective(Directive::DEFAULT, 'none')
            ->addDirective(Directive::CONNECT, 'self')
            ->addDirective(Directive::FORM, 'self')
            ->addDirective(Directive::IMG, 'self')
            ->addDirective(Directive::SCRIPT, 'self')
            ->addDirective(Directive::STYLE, 'self')
            ->addDirective(Directive::MEDIA, 'self');
    }
}