<?php

namespace Spatie\Csp\Presets;

use Spatie\Csp\Directive;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;

class SurveyMonkey implements Preset
{
    public function configure(Policy $policy): void
    {
        $policy
            ->add(Directive::FRAME, ['https://www.surveymonkey.com']);
    }
}
