<?php

namespace Spatie\Csp\Policies;

use Spatie\Csp\Directive;

class GooglePolicy extends Policy
{
    public function configure(): void
    {
        $this
            ->addNonceForDirective(Directive::SCRIPT)
            ->addDirective(Directive::SCRIPT, '*.googletagmanager.com')
            ->addDirective(Directive::IMG, '*.google-analytics.com')
            ->addDirective(Directive::IMG, '*.analytics.google.com')
            ->addDirective(Directive::IMG, '*.googletagmanager.com')
            ->addDirective(Directive::IMG, '*.g.doubleclick.net')
            ->addDirective(Directive::IMG, '*.google.com')
            ->addDirective(Directive::CONNECT, '*.google-analytics.com')
            ->addDirective(Directive::CONNECT, '*.analytics.google.com')
            ->addDirective(Directive::CONNECT, '*.googletagmanager.com')
            ->addDirective(Directive::CONNECT, '*.g.doubleclick.net')
            ->addDirective(Directive::CONNECT, '*.google.com');
    }
}
