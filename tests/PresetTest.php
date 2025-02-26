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
    Presets\AdobeFonts::class,
    Presets\Basic::class,
    Presets\Fathom::class,
    Presets\GoogleFonts::class,
    Presets\Google::class,
    Presets\HubSpot::class,
    Presets\JsDelivr::class,
    Presets\Tolt::class,
]);
