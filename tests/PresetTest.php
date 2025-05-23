<?php

use Spatie\Csp\Policy;
use Spatie\Csp\Preset;
use Spatie\Csp\Presets;

it(
    'stringifies',
    /** @param class-string<Preset> $presetClass */
    function (string $presetClass): void {
        config(['csp.nonce_enabled' => false]);

        $policy = new Policy();

        $preset = new $presetClass;
        $preset->configure($policy);

        expect(str_replace(';', PHP_EOL, $policy->getContents()))
            ->toMatchSnapshot();
    }
)->with([
    Presets\Basic::class,
    Presets\AdobeFonts::class,
    Presets\BunnyFonts::class,
    Presets\Clarity::class,
    Presets\CloudflareTurnstile::class,
    Presets\CloudflareWebAnalytics::class,
    Presets\Fathom::class,
    Presets\GoogleAnalytics::class,
    Presets\GoogleFonts::class,
    Presets\GoogleRecaptcha::class,
    Presets\GoogleTagManager::class,
    Presets\GoogleTlds::class,
    Presets\Hcaptcha::class,
    Presets\Hireroad::class,
    Presets\HotJar::class,
    Presets\HubSpot::class,
    Presets\Intercom::class,
    Presets\JsDelivr::class,
    Presets\MetaPixel::class,
    Presets\PlausibleAnalytics::class,
    Presets\Posthog::class,
    Presets\Stripe::class,
    Presets\TicketTailor::class,
    Presets\Tolt::class,
    Presets\Vimeo::class,
]);
