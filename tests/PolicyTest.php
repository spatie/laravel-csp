<?php

use Spatie\Csp\Policies;

/** @var class-string<Policies\Policy>[] $policies */
$policies = [
    Policies\BasicPolicy::class,
    Policies\FathomPolicy::class,
    Policies\GoogleFontsPolicy::class,
    Policies\GooglePolicy::class,
    Policies\HubSpotPolicy::class,
    Policies\JsDelivrPolicy::class,
    Policies\ToltPolicy::class,
];

foreach ($policies as $policyClass) {
    it('stringifies ' . $policyClass, function () use ($policyClass): void {
        config(['csp.nonce_enabled' => false]);

        $policy = new $policyClass;
        $policy->configure();

        expect((string) $policy)->toMatchSnapshot();
    });
}
