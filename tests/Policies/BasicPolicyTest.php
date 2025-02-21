<?php

use Spatie\Csp\Policies\BasicPolicy;

it('stringifies BasicPolicy', function (): void {
    config(['csp.nonce_enabled' => false]);

    $policy = new BasicPolicy();
    $policy->configure();;

    expect((string) $policy)->toMatchSnapshot();
});
