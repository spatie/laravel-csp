<?php

namespace Spatie\Csp\Presets;

use Spatie\Csp\Directive;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;

class HubSpot implements Preset
{
    public function configure(Policy $policy): void
    {
        $policy
            ->add(Directive::SCRIPT, '*.hsadspixel.net')
            ->add(Directive::SCRIPT, '*.hs-analytics.net')
            ->add(Directive::CONNECT, '*.hubapi.com')
            ->add([Directive::SCRIPT, Directive::IMG, Directive::CONNECT], [
                'js.hscta.net',
                'js-eu1.hscta.net',
            ])
            ->add(Directive::IMG, 'no-cache.hubspot.com')
            ->add([Directive::SCRIPT, Directive::IMG, Directive::CONNECT, Directive::FRAME], '*.hubspot.com')
            ->add(Directive::FRAME, [
                '*.hs-sites.com',
                '*.hs-sites-eu1.com',
            ])
            ->add(Directive::SCRIPT, 'static.hsappstatic.net')
            ->add(Directive::SCRIPT, '*.usemessages.com')
            ->add([Directive::SCRIPT, Directive::CONNECT], '*.hs-banner.com')
            ->add([Directive::SCRIPT, Directive::IMG, Directive::STYLE], [
                '*.hubspotusercontent00.net',
                '*.hubspotusercontent10.net',
                '*.hubspotusercontent20.net',
                '*.hubspotusercontent30.net',
                '*.hubspotusercontent40.net',
            ])
            ->add([Directive::SCRIPT, Directive::IMG, Directive::FRAME], '*.hubspot.net')
            ->add(Directive::FRAME, [
                'play.hubspotvideo.com',
                'play-eu1.hubspotvideo.com',
            ])
            ->add([Directive::IMG, Directive::STYLE], 'cdn2.hubspot.net')
            ->add([Directive::SCRIPT, Directive::CONNECT], '*.hscollectedforms.net')
            ->add(Directive::SCRIPT, '*.hsleadflows.net')
            ->add([Directive::SCRIPT, Directive::IMG, Directive::FRAME], '*.hsforms.net')
            ->add([Directive::SCRIPT, Directive::IMG, Directive::FRAME, Directive::CONNECT, Directive::CHILD], '*.hsforms.com')
            ->add(Directive::SCRIPT, '*.hs-scripts.com')
            ->add(Directive::SCRIPT, '*.hubspotfeedback.com')
            ->add(Directive::SCRIPT, 'feedback.hubapi.com')
            ->add(Directive::SCRIPT, 'feedback-eu1.hubapi.com');
    }
}
