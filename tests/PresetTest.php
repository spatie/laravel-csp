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
    Presets\Alchemer::class,
    Presets\Algolia::class,
    Presets\BunnyFonts::class,
    Presets\Bootstrap::class,
    Presets\Clarity::class,
    Presets\CloudflareCdn::class,
    Presets\CloudflareTurnstile::class,
    Presets\CloudflareWebAnalytics::class,
    Presets\Fathom::class,
    Presets\GoogleAnalytics::class,
    Presets\GoogleFonts::class,
    Presets\GoogleLookerStudio::class,
    Presets\GoogleRecaptcha::class,
    Presets\GoogleTagManager::class,
    Presets\GoogleTlds::class,
    Presets\HeapAnalytics::class,
    Presets\Hcaptcha::class,
    Presets\Hireroad::class,
    Presets\HotJar::class,
    Presets\HubSpot::class,
    Presets\Intercom::class,
    Presets\JsDelivr::class,
    Presets\JQuery::class,
    Presets\Maze::class,
    Presets\MetaPixel::class,
    Presets\PlausibleAnalytics::class,
    Presets\Posthog::class,
    Presets\Sentry::class,
    Presets\Stripe::class,
    Presets\SurveyMonkey::class,
    Presets\TicketTailor::class,
    Presets\Tolt::class,
    Presets\Vimeo::class,
    Presets\VisualWebsiteOptimizer::class,
    Presets\Whereby::class,
]);
