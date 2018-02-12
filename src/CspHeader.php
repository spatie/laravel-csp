<?php

namespace Spatie\LaravelCsp;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Config\Repository;

class CspHeader
{
    /** @var \Illuminate\Http\Response */
    protected $response;

    /** @var string */
    protected $policy;

    public function __construct(Repository $config)
    {
        $setup = (new CspSetupProcessor)->getSetup('csp');

        $this->policy = (new CspPolicyFactory)->create($setup);
    }

    public function handle(Request $request, Closure $next)
    {
        $this->response = $next($request);

        $this->addCSPHeaderToResponse();

        return $this->response;
    }

    protected function addCSPHeaderToResponse()
    {
        $this->response->headers->set('Content-Security-Policy', $this->policy, false);
    }
}
