<?php

namespace Spatie\Csp;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class AddCspHeaders
{
    public function handle(Request $request, Closure $next, $customProfileClass = null)
    {
        $response = $next($request);

        $this
            ->getProfiles($customProfileClass, $response)
            ->filter->shouldBeApplied($request, $response)
            ->each->applyTo($response);

        return $response;
    }

    protected function getProfiles(string $customProfileClass = null, $response): Collection
    {
        $profiles = collect();

        if ($customProfileClass) {
            $profiles->push(ProfileFactory::create($customProfileClass));

            return $profiles;
        }

        $profileClass = config('csp.profile');

        if (! empty($profileClass)) {
            $profiles->push(ProfileFactory::create($profileClass));
        }

        $reportOnlyProfileClass = config('csp.report_only_profile');

        if (! empty($reportOnlyProfileClass)) {
            $profile = ProfileFactory::create($reportOnlyProfileClass);

            $profile->reportOnly();

            $profiles->push($profile);
        }

        return $profiles;
    }
}
