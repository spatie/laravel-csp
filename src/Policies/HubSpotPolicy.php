<?php

namespace Spatie\Csp\Policies;

use Spatie\Csp\Directive;

class HubSpotPolicy extends Policy
{
    public function configure(): void
    {
        $this
            ->addDirective(Directive::SCRIPT, '*.hsadspixel.net')
            ->addDirective(Directive::SCRIPT, '*.hs-analytics.net')
            ->addDirective(Directive::CONNECT, '*.hubapi.com')
            ->addDirective(Directive::SCRIPT, 'js.hscta.net')
            ->addDirective(Directive::IMG, 'js.hscta.net')
            ->addDirective(Directive::CONNECT, 'js.hscta.net')
            ->addDirective(Directive::SCRIPT, 'js-eu1.hscta.net')
            ->addDirective(Directive::IMG, 'js-eu1.hscta.net')
            ->addDirective(Directive::CONNECT, 'js-eu1.hscta.net')
            ->addDirective(Directive::IMG, 'no-cache.hubspot.com')
            ->addDirective(Directive::IMG, 'no-cache.hubspot.com')
            ->addDirective(Directive::SCRIPT, '*.hubspot.com')
            ->addDirective(Directive::IMG, '*.hubspot.com')
            ->addDirective(Directive::CONNECT, '*.hubspot.com')
            ->addDirective(Directive::FRAME, '*.hubspot.com')
            ->addDirective(Directive::FRAME, '*.hs-sites.com')
            ->addDirective(Directive::FRAME, '*.hs-sites-eu1.com')
            ->addDirective(Directive::SCRIPT, 'static.hsappstatic.net')
            ->addDirective(Directive::SCRIPT, '*.usemessages.com')
            ->addDirective(Directive::SCRIPT, '*.hs-banner.com')
            ->addDirective(Directive::CONNECT, '*.hs-banner.com')
            ->addDirective(Directive::SCRIPT, '*.hubspotusercontent00.net')
            ->addDirective(Directive::IMG, '*.hubspotusercontent00.net')
            ->addDirective(Directive::STYLE, '*.hubspotusercontent00.net')
            ->addDirective(Directive::SCRIPT, '*.hubspotusercontent10.net')
            ->addDirective(Directive::IMG, '*.hubspotusercontent10.net')
            ->addDirective(Directive::STYLE, '*.hubspotusercontent10.net')
            ->addDirective(Directive::SCRIPT, '*.hubspotusercontent20.net')
            ->addDirective(Directive::IMG, '*.hubspotusercontent20.net')
            ->addDirective(Directive::STYLE, '*.hubspotusercontent20.net')
            ->addDirective(Directive::SCRIPT, '*.hubspotusercontent30.net')
            ->addDirective(Directive::IMG, '*.hubspotusercontent30.net')
            ->addDirective(Directive::STYLE, '*.hubspotusercontent30.net')
            ->addDirective(Directive::SCRIPT, '*.hubspotusercontent40.net')
            ->addDirective(Directive::IMG, '*.hubspotusercontent40.net')
            ->addDirective(Directive::STYLE, '*.hubspotusercontent40.net')
            ->addDirective(Directive::SCRIPT, '*.hubspot.net')
            ->addDirective(Directive::IMG, '*.hubspot.net')
            ->addDirective(Directive::FRAME, '*.hubspot.net')
            ->addDirective(Directive::FRAME, 'play.hubspotvideo.com')
            ->addDirective(Directive::FRAME, 'play-eu1.hubspotvideo.com')
            ->addDirective(Directive::IMG, 'cdn2.hubspot.net')
            ->addDirective(Directive::STYLE, 'cdn2.hubspot.net')
            ->addDirective(Directive::SCRIPT, '*.hscollectedforms.net')
            ->addDirective(Directive::CONNECT, '*.hscollectedforms.net')
            ->addDirective(Directive::SCRIPT, '*.hsleadflows.net')
            ->addDirective(Directive::SCRIPT, '*.hsforms.net')
            ->addDirective(Directive::IMG, '*.hsforms.net')
            ->addDirective(Directive::FRAME, '*.hsforms.net')
            ->addDirective(Directive::SCRIPT, '*.hsforms.com')
            ->addDirective(Directive::IMG, '*.hsforms.com')
            ->addDirective(Directive::FRAME, '*.hsforms.com')
            ->addDirective(Directive::CONNECT, '*.hsforms.com')
            ->addDirective(Directive::CHILD, '*.hsforms.com')
            ->addDirective(Directive::SCRIPT, '*.hs-scripts.com')
            ->addDirective(Directive::SCRIPT, '*.hubspotfeedback.com')
            ->addDirective(Directive::SCRIPT, 'feedback.hubapi.com')
            ->addDirective(Directive::SCRIPT, 'feedback-eu1.hubapi.com');
    }
}
