<?php

namespace Spatie\Csp;

use Closure;
use Illuminate\Http\Request;
use Spatie\Csp\Exceptions\InvalidCspProfile;
use Symfony\Component\HttpFoundation\Response;

class AddCspHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (config('csp.enabled')) {
            $this->addCspHeaders($response);
        }

        return $response;
    }

    protected function addCspHeaders(Response $response)
    {
        $profile = app(Profile::class);

        if (! is_a($profile, Profile::class, true)) {
            throw InvalidCspProfile::create($profile);
        }

        $profile->applyTo($response);
    }
}
