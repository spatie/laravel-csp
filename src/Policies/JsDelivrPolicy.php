<?php

namespace Spatie\Csp\Policies;

use Spatie\Csp\Directive;

class JsDelivrPolicy extends Policy
{

    public function configure(): void
    {
        $this
            ->addDirective(Directive::SCRIPT, 'cdn.jsdelivr.net')
            ->addDirective(Directive::STYLE, 'cdn.jsdelivr.net')
            ->addDirective(Directive::FONT, 'cdn.jsdelivr.net');
    }
}
