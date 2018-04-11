<?php

namespace Spatie\Csp\Policies;

use Spatie\Csp\Directive;

class Basic extends Policy
{
    public function configure()
    {
        $this
            ->addDirective(Directive::BASE, Directive::VALUE_SELF)
            ->addDirective(Directive::CONNECT, Directive::VALUE_SELF)
            ->addDirective(Directive::DEFAULT, Directive::VALUE_SELF)
            ->addDirective(Directive::FORM_ACTION, Directive::VALUE_SELF)
            ->addDirective(Directive::IMG, Directive::VALUE_SELF)
            ->addDirective(Directive::MEDIA, Directive::VALUE_SELF)
            ->addDirective(Directive::OBJECT, Directive::VALUE_NONE)
            ->addDirective(Directive::SCRIPT, Directive::VALUE_SELF)
            ->addDirective(Directive::STYLE, Directive::VALUE_SELF)
            ->addNonceForDirective(Directive::SCRIPT)
            ->addNonceForDirective(Directive::STYLE);
    }
}
