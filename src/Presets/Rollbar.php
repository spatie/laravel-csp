<?php declare(strict_types=1);

namespace Spatie\Csp\Presets;

use Spatie\Csp\Directive;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;

class Rollbar implements Preset
{
    public function configure(Policy $policy): void
    {
        $policy
            ->add(Directive::CONNECT, 'https://api.rollbar.com')
            ->add(Directive::SCRIPT, 'https://cdn.rollbar.com')
        ;
    }
}
