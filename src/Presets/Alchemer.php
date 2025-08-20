<?php

namespace Spatie\Csp\Presets;

use Spatie\Csp\Directive;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;

class Alchemer implements Preset
{
    public function configure(Policy $policy): void
    {
        $policy
            ->add(Directive::FRAME, ['survey.alchemer.com']);
    }
}
