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

        if (
            $response->headers->has('Content-Security-Policy')
            || $response->headers->has('Content-Security-Policy-Report-Only')
        ) {
            return $response;
        }

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

        foreach (config('csp.policies', []) as $policyClass) {
            $policies->push(
                PolicyFactory::create($policyClass)
            );
        }

        foreach (config('csp.report_only_policies', []) as $reportOnlyPolicyClass) {
            $policies->push(
                PolicyFactory::create($reportOnlyPolicyClass)
                    ->reportOnly()
            );
        }

        return $policies;
    }
}
