<?php

namespace Spatie\Csp;

use Illuminate\Support\Arr;

class CspMetaTag
{
    public function __construct(
        protected Policy $policy = new Policy(),
        protected Policy $reportOnlyPolicy = new Policy(),
    ) {
    }

    public static function create(string|array $presets = [], bool $reportOnly = false): static
    {
        if (! config('csp.enabled')) {
            return new static();
        }

        $presets = Arr::wrap($presets);

        if (! empty($presets)) {
            if ($reportOnly) {
                $reportOnlyPolicy = Policy::create(
                    presets: $presets,
                    reportUri: config('csp.report_uri')
                );

                return new static(reportOnlyPolicy: $reportOnlyPolicy);
            } else {
                $policy = Policy::create(
                    presets: $presets,
                    reportUri: config('csp.report_uri'),
                );

                return new static(policy: $policy);
            }
        }

        $policy = Policy::create(
            presets: config('csp.presets'),
            directives: config('csp.directives'),
            reportUri: config('csp.report_uri'),
        );

        $reportOnlyPolicy = Policy::create(
            presets: config('csp.report_only_presets'),
            directives: config('csp.report_only_directives'),
            reportUri: config('csp.report_uri'),
        );

        return new static($policy, $reportOnlyPolicy);
    }

    public static function createReportOnly(string|array $presets = []): static
    {
        return static::create($presets, reportOnly: true);
    }

    public function __toString(): string
    {
        $tags = [];

        if (! $this->policy->isEmpty()) {
            $tags[] = "<meta http-equiv=\"Content-Security-Policy\" content=\"{$this->policy->getContents()}\">";
        }

        if (! $this->reportOnlyPolicy->isEmpty()) {
            $tags[] = "<meta http-equiv=\"Content-Security-Policy-Report-Only\" content=\"{$this->reportOnlyPolicy->getContents()}\">";
        }

        return implode(PHP_EOL, $tags);
    }
}
