<?php

namespace Spatie\LaravelCsp;

use Closure;
use Illuminate\Config\Repository;

class CspHeader
{
    /** @var array */
    protected $config;

    /** @var string */
    protected $policy;

    public function __construct(Repository $config)
    {
        $this->config = $config->get('csp');
    }

    public function handle($request, Closure $next)
    {
        return $this->addCSPHeaderToResponse($next($request));
    }

    protected function addCSPHeaderToResponse($response)
    {
        return $response->header('Content-Security-Policy', $this->policy);
    }
}
