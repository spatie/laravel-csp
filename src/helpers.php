<?php

use Spatie\Csp\Exceptions\MissingCspMetaTagPolicy;
use Spatie\Csp\Policies\Policy;
use Spatie\Csp\PolicyFactory;

if (! function_exists('csp_nonce')) {
    function csp_nonce(): string
    {
        return app('csp-nonce');
    }
}

if (! function_exists('csp_meta_tag')) {
    function csp_meta_tag(string|array $policyClass): string
    {
        $policies = collect($policyClass)
            ->filter()
            ->map(fn (string $policy) => PolicyFactory::create($policy));

        if ($policies->isEmpty()) {
            throw MissingCspMetaTagPolicy::create();
        }

        if (! config('csp.enabled')) {
            return '';
        }

        $header = $policies->first()->prepareHeader();

        $content = $policies
            ->map(function (Policy $policy) {
                $policy->configure();

                return (string)$policy;
            })
            ->join(';');

        return "<meta http-equiv=\"{$header}\" content=\"{$content}\">";
    }
}
