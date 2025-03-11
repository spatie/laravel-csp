<?php

namespace Spatie\Csp\Presets;

use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;

class AdobeFonts implements Preset
{
    public function configure(Policy $policy): void
    {
        $policy
            ->add(Directive::CONNECT, 'performance.typekit.net')
            ->add(Directive::FONT, 'use.typekit.net')
            ->add(Directive::IMG, [Keyword::SELF, 'p.typekit.net'])
            ->add(Directive::SCRIPT, [Keyword::SELF, 'use.typekit.net'])
            ->add(Directive::STYLE, [Keyword::SELF, Keyword::UNSAFE_INLINE, 'p.typekit.net', 'use.typekit.net']);
    }
}
