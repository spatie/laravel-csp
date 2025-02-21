<?php

namespace Spatie\Csp\Policies;

use Spatie\Csp\Directive;

class GoogleFontsPolicy extends Policy
{
    public function configure()
    {
        $this
            ->addDirective(Directive::STYLE, 'fonts.googleapis.com')
            ->addDirective(Directive::FONT, 'fonts.gstatic.com');
    }
}
