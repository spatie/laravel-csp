<?php

namespace Spatie\Csp;

use Spatie\Csp\Profiles\Profile;
use Spatie\Csp\Exceptions\InvalidCspProfile;

class ProfileFactory
{
    public static function create(string $className): Profile
    {
        $profile = app($className);

        if (! is_a($profile, Profile::class, true)) {
            throw InvalidCspProfile::create($profile);
        }

        if (! empty(config('csp.report_uri'))) {
            $profile->reportTo(config('csp.report_uri'));
        }

        return $profile;
    }
}
