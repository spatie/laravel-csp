<?php

namespace Spatie\Csp\Policies;

use Spatie\Csp\Directive;
use Spatie\Csp\Value;

class Basic extends Policy
{
    public function configure()
    {
        $this
            ->addDirective(Directive::BASE, Value::SELF)
            ->addDirective(Directive::CONNECT, Value::SELF)
            ->addDirective(Directive::DEFAULT, Value::SELF)
            ->addDirective(Directive::FORM_ACTION, Value::SELF)
            ->addDirective(Directive::IMG, Value::SELF)
            ->addDirective(Directive::MEDIA, Value::SELF)
            ->addDirective(Directive::OBJECT, Value::NONE)
            ->addDirective(Directive::SCRIPT, Value::SELF)
            ->addDirective(Directive::STYLE, Value::SELF)
            ->addNonceForDirective(Directive::SCRIPT)
            ->addNonceForDirective(Directive::STYLE);
    }
}
