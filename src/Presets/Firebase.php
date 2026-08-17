<?php

namespace Spatie\Csp\Presets;

use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;

class Firebase implements Preset
{
    public function configure(Policy $policy): void
    {
        $policy
            ->add(Directive::SCRIPT, [
                'https://www.gstatic.com',
                'https://www.googleapis.com',
            ])
            ->add(Directive::CONNECT, [
                'https://fcmregistrations.googleapis.com',
                'https://firebaseinstallations.googleapis.com',
                'https://firebase.googleapis.com',
                'https://fcm.googleapis.com',
                'https://www.googleapis.com',
            ])
            ->add(Directive::WORKER, [Keyword::SELF]);
    }
}
