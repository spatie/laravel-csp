<?php

namespace Spatie\Csp\Policies;

use Spatie\Csp\Directive;

class ToltPolicy extends Policy
{
    public function configure(): void
    {
        $this
            ->addDirective(Directive::SCRIPT, 'cdn.tolt.io');
    }
}
