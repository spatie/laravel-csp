<?php

use Spatie\Csp\Policies\HubSpotPolicy;

it('stringifies HubSpotPolicy()', function (): void {
    config(['csp.nonce_enabled' => false]);

    $policy = new HubSpotPolicy();
    $policy->configure();

    expect((string) $policy)->toMatchSnapshot();
})->skip(version_compare(phpversion(), '8.2.0') === -1, 'Pest snapshot tests not working on earlier versions');
