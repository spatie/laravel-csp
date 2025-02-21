<?php

namespace Spatie\Csp\Policies;

use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;

class AdobeFontsPolicy extends Policy
{
    public function configure(): void
    {
        $this
            ->addDirective(Directive::SCRIPT, [Keyword::SELF, 'use.typekit.net'])
            ->addDirective(Directive::STYLE, [Keyword::SELF, Keyword::UNSAFE_INLINE, 'use.typekit.net'])
            ->addDirective(Directive::IMG, [Keyword::SELF, 'p.typekit.net'])
            ->addDirective(Directive::CONNECT, 'performance.typekit.net');
    }
}
