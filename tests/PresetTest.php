<?php

use Spatie\Csp\Policy;
use Spatie\Csp\Presets;

/** @var class-string<\Spatie\Csp\Preset>[] $presets */
$presets = [
    Presets\AdobeFonts::class,
    Presets\Basic::class,
    Presets\Fathom::class,
    Presets\GoogleFonts::class,
    Presets\Google::class,
    Presets\HubSpot::class,
    Presets\JsDelivr::class,
    Presets\Tolt::class,
];

foreach ($presets as $presetClass) {
    it('stringifies ' . $presetClass, function () use ($presetClass): void {
        config(['csp.nonce_enabled' => false]);

        $policy = new Policy();

        $preset = new $presetClass;
        $preset->configure($policy);

        expect(str_replace(';', PHP_EOL, $policy->getContents()))
            ->toMatchSnapshot();
    });
}
