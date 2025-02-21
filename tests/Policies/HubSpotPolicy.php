<?php

use Spatie\Csp\Policies\HubSpotPolicy;

it('stringifies HubSpotPolicy()', function (): void {
    config(['csp.nonce_enabled' => false]);

    $policy = new HubSpotPolicy();
    $policy->configure();;

    expect((string) $policy)->toMatchSnapshot();
});
