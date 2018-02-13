<?php

namespace Spatie\LaravelCsp;

use Closure;
use Illuminate\Http\Request;
use Spatie\LaravelCsp\Profile\Csp;
use Illuminate\Database\Eloquent\Collection;

class CspHeaderSetter
{
    /** @var \Illuminate\Http\Response */
    protected $response;

    /** @var string */
    protected $policy;

    /** @var Collection */
    protected $profile;

    public function __construct()
    {
        $profile = $this->getCspProfileClass();

        $this->profile = $profile->profileSetup();

        $this->profile = $profile->profile;
    }

    public function handle(Request $request, Closure $next)
    {
        $this->response = $next($request);

        $this->addCspHeaderToResponse();

        return $this->response;
    }

    protected function addCspHeaderToResponse()
    {
        $this->profileToPolicy();

        $this->response->headers->set('Content-Security-Policy', $this->policy, false);
    }

    public function profileToPolicy()
    {
        $policy = $this->profile->map(function (Collection $value, string $key) {
            $value = $value->implode(' ');

            return "{$key}: {$value};";
        });

        $this->policy = $policy->implode(' ');
    }

    protected function getCspProfileClass(): Csp
    {
        return config('csp.csp_profile');
    }
}
