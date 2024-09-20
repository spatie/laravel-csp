<?php

namespace Spatie\Csp;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class AddCspHeaders
{
    public function handle(Request $request, Closure $next, ?string $customPolicyClass = null)
    {
        $response = $next($request);

        $this
            ->getPolicies($customPolicyClass)
            ->filter->shouldBeApplied($request, $response)
            ->each->applyTo($response);

        return $response;
    }

    protected function getPolicies(?string $customPolicyClass = null): Collection
    {
        $policies = collect();

        if ($customPolicyClass) {
            $policies->push(PolicyFactory::create($customPolicyClass));

            return $policies;
        }

        $policyClass = config('csp.policy');

        if (! empty($policyClass)) {
            $policies->push(PolicyFactory::create($policyClass));
        }

        $reportOnlyPolicyClass = config('csp.report_only_policy');

        if (! empty($reportOnlyPolicyClass)) {
            $policy = PolicyFactory::create($reportOnlyPolicyClass);

            $policy->reportOnly();

            $policies->push($policy);
        }

        return $policies;
    }
}
