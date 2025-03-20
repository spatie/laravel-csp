<?php

namespace Spatie\Csp\Presets;

use Spatie\Csp\Directive;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;

class Intercom implements Preset
{
    public function configure(Policy $policy): void
    {
        $policy
            ->add(Directive::SCRIPT_ELEM, [
                'https://app.intercom.io',
                'https://widget.intercom.io',
                'https://js.intercomcdn.com',
            ])
            ->add(Directive::CONNECT, [
                'https://via.intercom.io',
                'https://api.intercom.io',
                'https://api.au.intercom.io',
                'https://api.eu.intercom.io',
                'https://api-iam.intercom.io',
                'https://api-iam.eu.intercom.io',
                'https://api-iam.au.intercom.io',
                'https://api-ping.intercom.io',
                'https://nexus-websocket-a.intercom.io',
                'wss://nexus-websocket-a.intercom.io',
                'https://nexus-websocket-b.intercom.io',
                'wss://nexus-websocket-b.intercom.io',
                'https://nexus-europe-websocket.intercom.io',
                'wss://nexus-europe-websocket.intercom.io',
                'https://nexus-australia-websocket.intercom.io',
                'wss://nexus-australia-websocket.intercom.io',
                'https://uploads.intercomcdn.com',
                'https://uploads.intercomcdn.eu',
                'https://uploads.au.intercomcdn.com',
                'https://uploads.eu.intercomcdn.com',
                'https://uploads.intercomusercontent.com',
            ])
            ->add(Directive::CHILD, [
                'https://intercom-sheets.com',
                'https://www.intercom-reporting.com',
                'https://www.youtube.com',
                'https://player.vimeo.com',
                'https://fast.wistia.net',
            ])
            ->add(Directive::FONT, [
                'https://js.intercomcdn.com',
                'https://fonts.intercomcdn.com',
            ])
            ->add(Directive::FORM_ACTION, [
                'https://intercom.help',
                'https://api-iam.intercom.io',
                'https://api-iam.eu.intercom.io',
                'https://api-iam.au.intercom.io',
            ])
            ->add(Directive::MEDIA, [
                'https://js.intercomcdn.com',
                'https://downloads.intercomcdn.com',
                'https://downloads.intercomcdn.eu',
                'https://downloads.au.intercomcdn.com',
            ])
            ->add(Directive::IMG, [
                'https://js.intercomcdn.com',
                'https://static.intercomassets.com',
                'https://downloads.intercomcdn.com',
                'https://downloads.intercomcdn.eu',
                'https://downloads.au.intercomcdn.com',
                'https://uploads.intercomusercontent.com',
                'https://gifs.intercomcdn.com',
                'https://video-messages.intercomcdn.com',
                'https://messenger-apps.intercom.io',
                'https://messenger-apps.eu.intercom.io',
                'https://messenger-apps.au.intercom.io',
                'https://*.intercom-attachments-1.com',
                'https://*.intercom-attachments.eu',
                'https://*.au.intercom-attachments.com',
                'https://*.intercom-attachments-2.com',
                'https://*.intercom-attachments-3.com',
                'https://*.intercom-attachments-4.com',
                'https://*.intercom-attachments-5.com',
                'https://*.intercom-attachments-6.com',
                'https://*.intercom-attachments-7.com',
                'https://*.intercom-attachments-8.com',
                'https://*.intercom-attachments-9.com',
                'https://static.intercomassets.eu',
                'https://static.au.intercomassets.com',
            ]);
    }
}
