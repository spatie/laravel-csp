<?php

namespace Spatie\Csp;

use Closure;
use Illuminate\Http\Request;
use Spatie\Csp\Exceptions\InvalidCspProfile;
use Spatie\Csp\Profiles\Profile;

class AddCspHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $profile = $this->getProfile();

        if ($profile->shouldBeApplied($request, $response)) {
            $profile->applyTo($response);
        }

        return $response;
    }

    protected function getProfile(): Profile
    {
        $profile = app(Profile::class);

        if (!is_a($profile, Profile::class, true)) {
            throw InvalidCspProfile::create($profile);
        }

        return $profile;
    }
}
