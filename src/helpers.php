<?php

use Spatie\Csp\Exceptions\MissingCspMetaTagPolicy;
use Spatie\Csp\PolicyFactory;

if (! function_exists('csp_nonce')) {
    function csp_nonce(): string
    {
        return app('csp-nonce');
    }
}

if (! function_exists('csp_meta_tag')) {
    function csp_meta_tag(string $policyClass): string
    {
        if (strlen($policyClass) === 0) {
            throw MissingCspMetaTagPolicy::create();
        }

        if (! config('csp.enabled')) {
            return '';
        }

        $policy = PolicyFactory::create($policyClass);

        return "<meta http-equiv=\"{$policy->prepareHeader()}\" content=\"{$policy->__toString()}\">";
    }
}
