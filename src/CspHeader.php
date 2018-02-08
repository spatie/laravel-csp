<?php

namespace Spatie\LaravelCsp;

use Closure;
use http\Env\Request;
use http\Env\Response;
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

    public function handle(Request $request, Closure $next): Response
    {
        $this->addCSPHeaderToResponse($request);

        return $next($request);
    }

    protected function addCSPHeaderToResponse($content): Response
    {
        $this->createPolicyFromConfig();

        return response($content)->header('Content-Security-Policy', $this->policy);
    }

    protected function getSetupToUse():string
    {
        if (!array_has($this->config['setups'], $this->config['default'])) {
            $this->config['default'] = 'strict';
        }

        return $this->config['default'];
    }

    protected function setupPartExists(string $setupPart): bool
    {
        if (array_has($this->config['setup-parts'], $setupPart)) {
            return true;
        }

        return false;
    }

    protected function createPolicyFromConfig()
    {
        $setupToUse = $this->getSetupToUse();

        $setupArray = $this->config['setups.' . $setupToUse];

        $filteredSetup = array_where($setupArray, function ($setupPart) {
            return $this->setupPartExists($setupPart);
        });

        $setup = array_map(function ($setupPart) {
            return $this->config['setup-parts.'.$setupPart];
        }, $filteredSetup);

        /** TODO: creating actual policy */
        $this->policy = implode(" ", array_flatten($setup));
    }
}
