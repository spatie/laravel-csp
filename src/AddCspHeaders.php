<?php

namespace Spatie\Csp;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class AddCspHeaders
{
    public function handle(Request $request, Closure $next, $customPolicyClass = null)
    {
        $response = $next($request);

        $this
            ->getPolicys($customPolicyClass, $response)
            ->filter->shouldBeApplied($request, $response)
            ->each->applyTo($response);

        return $response;
    }

    protected function getPolicys(string $customPolicyClass = null): Collection
    {
        $policys = collect();

        if ($customPolicyClass) {
            $policys->push(PolicyFactory::create($customPolicyClass));

            return $policys;
        }

        $policyClass = config('csp.policy');

        if (! empty($policyClass)) {
            $policys->push(PolicyFactory::create($policyClass));
        }

        $reportOnlyPolicyClass = config('csp.report_only_policy');

        if (! empty($reportOnlyPolicyClass)) {
            $policy = PolicyFactory::create($reportOnlyPolicyClass);

            $policy->reportOnly();

            $policys->push($policy);
        }

        return $policys;
    }
}
