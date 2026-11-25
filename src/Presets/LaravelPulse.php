<?php

namespace Spatie\Csp\Presets;

use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;
use Spatie\Csp\Scheme;

class LaravelPulse implements Preset
{
    public function configure(Policy $policy): void
    {
        $policy
            ->add(Directive::BASE, Keyword::SELF)
            ->add(Directive::CONNECT, Keyword::SELF)
            ->add(Directive::DEFAULT, Keyword::NONE)
            ->add(Directive::FONT, 'https://fonts.bunny.net/figtree/files/')
            ->add(Directive::FORM_ACTION, Keyword::NONE)
            ->add(Directive::FRAME_ANCESTORS, Keyword::NONE)
            ->add(Directive::IMG, [Scheme::DATA, 'https://gravatar.com/avatar', 'https://gravatar.com/avatar/', 'https://unavatar.io/'])
            ->add(Directive::STYLE, [Keyword::UNSAFE_INLINE, 'https://fonts.bunny.net/css'])
            ->add(Directive::SCRIPT, [Keyword::UNSAFE_INLINE, Keyword::UNSAFE_EVAL]);
    }
}
