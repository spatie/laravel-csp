<?php

namespace Spatie\Csp;

use Illuminate\Support\Arr;
use Spatie\Csp\Exceptions\MissingCspMetaTagPolicy;

class CspMetaTag
{
    public function __construct(
        protected Policy $policy,
        protected bool $reportOnly = false,
    ) {
    }

    public static function create(string|array $presets, bool $reportOnly = false): static
    {
        if (! config('csp.enabled')) {
            return new static(Policy::create([]));
        }

        $presets = Arr::wrap($presets);

        if (empty($presets)) {
            $presets = $reportOnly
                ? config('csp.report_only_presets', [])
                : config('csp.presets', []);
        }

        $policy = Policy::create($presets, config('csp.report_uri'));

        if ($policy->isEmpty()) {
            throw MissingCspMetaTagPolicy::create();
        }

        return new static($policy, $reportOnly);
    }

    public static function createReportOnly(string|array $presets): static
    {
        return static::create($presets, reportOnly: true);
    }

    public function __toString(): string
    {
        if ($this->policy->isEmpty()) {
            return '';
        }

        $header = $this->reportOnly ? 'Content-Security-Policy-Report-Only' : 'Content-Security-Policy';

        return "<meta http-equiv=\"{$header}\" content=\"{$this->policy->getContents()}\">";
    }
}
