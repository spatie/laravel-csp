<?php

namespace Spatie\Csp\Presets;

use Spatie\Csp\Directive;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;

class Featurebase implements Preset
{
    // Allows the Featurebase SDK widgets (https://www.featurebase.app).
    // The SDK is served from do.featurebase.app and loads its icons from sdk.featurebase.app.
    // Its API, WebSocket and embedded portal run on *.featurebase.app, images are resized
    // through featurebase.app/cdn-cgi/image, and the widget uses Google Fonts.
    public function configure(Policy $policy): void
    {
        $policy
            ->add(Directive::SCRIPT, [
                'https://do.featurebase.app',
                'https://sdk.featurebase.app',
            ])
            ->add(Directive::STYLE, [
                'https://do.featurebase.app',
                'https://fonts.googleapis.com',
            ])
            ->add(Directive::FONT, 'https://fonts.gstatic.com')
            ->add(Directive::CONNECT, [
                'https://*.featurebase.app',
                'wss://*.featurebase.app',
            ])
            ->add(Directive::FRAME, 'https://*.featurebase.app')
            ->add(Directive::IMG, [
                'https://featurebase.app',
                'https://*.featurebase.app',
                'https://*.featurebase-attachments.com',
                'https://fb-usercontent.fra1.cdn.digitaloceanspaces.com',
            ])
            ->add(Directive::MEDIA, [
                'https://*.featurebase.app',
                'https://*.featurebase-attachments.com',
            ]);
    }
}
