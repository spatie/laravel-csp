<?php

namespace Spatie\Csp\Presets;

use Spatie\Csp\Directive;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;

class TicketTailor implements Preset
{
    public function configure(Policy $policy): void
    {
        $policy
            ->add([Directive::FRAME, Directive::SCRIPT], 'https://www.tickettailor.com')
            ->add([Directive::FRAME, Directive::SCRIPT, Directive::FONT, Directive::IMG, Directive::CONNECT, Directive::OBJECT, Directive::STYLE], 'https://cdn.tickettailor.com');
    }
}
