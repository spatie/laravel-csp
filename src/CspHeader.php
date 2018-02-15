<?php

namespace Spatie\LaravelCsp;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Spatie\LaravelCsp\Exceptions\InvalidCspProfileClass;

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

        if (config('csp.report_mode')) {
            $this->response->headers->set('Content-Security-Policy-Report-Only', $this->policy, false);
        }

        if (! config('csp.report_mode')) {
            $this->response->headers->set('Content-Security-Policy', $this->policy, false);
        }
    }

    /**
     * @return string
     * @throws \Spatie\LaravelCsp\Exceptions\InvalidCspProfileClass
     */
    protected function getCspProfileClass(): string
    {
        $className = config('csp.csp_profile');

        if (! is_a($className, Csp::class, true)) {
            throw InvalidCspProfileClass::create($className);
        }

        return $className;
    }

    protected function profileToPolicy()
    {
        $policy = $this->profile->map(function (Collection $value, string $key) {
            $value->transform(function ($content) {
                if (strpos($content, ':') === false) {
                    return "'{$content}'";
                }

                return $content;
            });

            $value = $value->implode(' ');

            return "{$key} {$value};";
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
