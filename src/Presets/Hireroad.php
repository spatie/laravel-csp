<?php

namespace Spatie\Csp\Presets;

use Spatie\Csp\Directive;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;

class Hireroad implements Preset
{
    // Vacancy filler directives for old hireroad branded sites since they have been migrated to hireroad
    public function configure(Policy $policy): void
    {
        $policy
            ->add([Directive::SCRIPT, Directive::FONT, Directive::STYLE], '*.hireroad.com')
            ->add([Directive::SCRIPT, Directive::FONT, Directive::STYLE], '*.vacancy-filler.co.uk')
            ->add(Directive::FONT, 'data:');
    }
}
