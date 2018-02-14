<?php

namespace Spatie\LaravelCsp;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CspHeader
{
    /** @var \Illuminate\Http\Response */
    protected $response;

    /** @var \Illuminate\Support\Collection */
    protected $profile;

    /** @var string */
    protected $policy;

    public function handle(Request $request, Closure $next)
    {
        $this->response = $next($request);

        if (config('csp.enabled')) {
            $this->addCspHeaderToResponse();
        }

        return $this->response;
    }

    protected function addCspHeaderToResponse()
    {
        $this->setupProfile();

        $this->profileToPolicy();

        $this->response->headers->set('Content-Security-Policy', $this->policy, false);
    }

    protected function getCspProfileClass(): string
    {
        return config('csp.csp_profile');
    }

    protected function profileToPolicy()
    {
        $policy = $this->profile->map(function (Collection $value, string $key) {
            $value = $value->implode(' ');

            return "{$key}: {$value};";
        });

        $this->policy = $policy->implode(' ');
    }

    protected function setupProfile()
    {
        $classToUse = $this->getCspProfileClass();

        $profileClass = new $classToUse;

        $profileClass->profileSetup();

        $this->profile = $profileClass->profile;
    }
}
