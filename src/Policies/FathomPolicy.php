<?php

namespace Spatie\Csp\Policies;

use Spatie\Csp\Directive;

class FathomPolicy extends Policy
{
    public function configure(): void
    {
        $this
            ->addDirective(Directive::SCRIPT, 'cdn.usefathom.com');
    }
}
