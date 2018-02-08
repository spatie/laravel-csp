<?php

namespace Spatie\LaravelCsp;

use Closure;
use Illuminate\Config\Repository;
use Illuminate\Http\Request;

class CspHeader
{
    /** @var \Illuminate\Http\Response */
    protected $response;

    /** @var array */
    protected $config;

    /** @var string */
    protected $policy;

    public function __construct(Repository $config)
    {
        $this->config = $config->get('csp');

        $this->policy = $this->createPolicyFromConfig();
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

    protected function getSetupToUse(): string
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

    protected function createSetup(): array
    {
        $setupToUse = $this->getSetupToUse();

        $setupArray = $this->config['setups'][$setupToUse];

        $filteredSetup = array_where($setupArray, function ($setupPart) {
            return $this->setupPartExists($setupPart);
        });

        $setup = array_map(function ($setupPart) {
            return $this->config['setup-parts'][$setupPart];
        }, $filteredSetup);

        return $setup;
    }

    protected function policyFromSetup(array $setup): array
    {
        $alreadyUsed = [];

        $policy = [];

        foreach ($setup as $setupPart) {
            foreach ($setupPart as $key => $value) {
                if (in_array($key, $alreadyUsed)) {
                    array_push(
                        $policy[$key],
                        implode(" ", array_flatten($value))
                    );
                }

                if (!in_array($key, $alreadyUsed)) {
                    array_push($alreadyUsed, $key);

                    $policy[$key][0] = implode(" ", array_flatten($value));
                }
            }
        }

        return $policy;
    }

    protected function createPolicyFromConfig(): string
    {
        $setup = $this->createSetup();

        $policy = $this->policyFromSetup($setup);

        $policyKeys = array_keys($policy);

        $policy = array_map(function ($key, $value) {
            return $key.': '.implode(' ', $value).';';
        }, $policyKeys, $policy);

        return implode(' ', $policy);
    }
}
