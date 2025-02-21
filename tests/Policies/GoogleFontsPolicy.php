<?php

use Spatie\Csp\Policies\GoogleFontsPolicy;

it('stringifies GoogleFontsPolicy', function (): void {
    config(['csp.nonce_enabled' => false]);

    $policy = new GoogleFontsPolicy();
    $policy->configure();;

    expect((string) $policy)->toMatchSnapshot();
});
