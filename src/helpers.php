<?php

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
        if (! config('csp.enabled')) {
            return '';
        }

        $policy = PolicyFactory::create($policyClass);

        return "<meta http-equiv=\"{$policy->prepareHeader()}\" content=\"{$policy->__toString()}\">";
    }
}
