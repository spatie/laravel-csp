<?php

namespace Spatie\Csp\Presets;

use Spatie\Csp\Directive;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;

class Bootstrap implements Preset
{
    public function configure(Policy $policy): void
    {
        $policy
            ->add([Directive::STYLE, Directive::FONT], [
                'data:',
                'https://maxcdn.bootstrapcdn.com',
            ]);
    }
}
