<?php

use Spatie\Csp\Policy;
use Spatie\Csp\Preset;
use Spatie\Csp\Presets;

it(
    'stringifies',
    /** @param class-string<Preset> $presetClass */
    function (string $presetClass): void {
        config(['csp.nonce_enabled' => false]);

        $policy = new Policy;

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
    Presets\Chargebee::class,
    Presets\Clarity::class,
    Presets\CloudflareCdn::class,
    Presets\CloudflareTurnstile::class,
    Presets\CloudflareWebAnalytics::class,
    Presets\Fathom::class,
    Presets\Firebase::class,
    Presets\GoogleAds::class,
    Presets\GoogleAnalytics::class,
    Presets\GoogleFonts::class,
    Presets\GoogleLookerStudio::class,
    Presets\GoogleMaps::class,
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
    Presets\Plain::class,
    Presets\PlausibleAnalytics::class,
    Presets\Posthog::class,
    Presets\Rollbar::class,
    Presets\Sentry::class,
    Presets\Stripe::class,
    Presets\SurveyMonkey::class,
    Presets\ThereThere::class,
    Presets\TicketTailor::class,
    Presets\Tolt::class,
    Presets\TrackJs::class,
    Presets\Vimeo::class,
    Presets\VisualWebsiteOptimizer::class,
    Presets\Whereby::class,
]);

it('only adds HTTPS Hotjar migration sources for scripts images and connections', function (bool $withBasic): void {
    config(['csp.nonce_enabled' => false]);

    $contents = Policy::create($withBasic
        ? [Presets\Basic::class, Presets\HotJar::class]
        : [Presets\HotJar::class]
    )->getContents();

    $self = $withBasic ? "'self' " : '';

    expect(explode(';', $contents))->toContain(
        "frame-src {$self}*.hotjar.com",
        "script-src {$self}*.hotjar.com https://*.hotjar.io",
        "font-src {$self}*.hotjar.com",
        "style-src {$self}*.hotjar.com",
        "img-src {$self}*.hotjar.com https://*.hotjar.io",
        "connect-src {$self}https://*.hotjar.com https://*.hotjar.io wss://*.hotjar.com",
    );

    expect($contents)->not->toContain('script-src-elem');
})->with([
    'standalone' => false,
    'with Basic' => true,
]);

it('combines the Basic and Intercom source directives', function (): void {
    config(['csp.nonce_enabled' => false]);

    $contents = Policy::create([
        Presets\Basic::class,
        Presets\Intercom::class,
    ])->getContents();

    expect($contents)
        ->toContain("frame-src 'self' https://intercom-sheets.com")
        ->toContain("script-src 'self' https://app.intercom.io")
        ->not->toContain('child-src')
        ->not->toContain('script-src-elem');
});
