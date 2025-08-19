<?php

namespace Spatie\Csp\Presets;

use Spatie\Csp\Directive;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;

class HeapAnalytics implements Preset
{
    public function configure(Policy $policy): void
    {
        $policy
            ->add(Directive::SCRIPT, [
                'https://cdn.heapanalytics.com',
            ])
            ->add([Directive::IMG, Directive::CONNECT], [
                'https://heapanalytics.com',
            ]);
    }
}
