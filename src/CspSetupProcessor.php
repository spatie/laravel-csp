<?php

namespace Spatie\LaravelCsp;

use Illuminate\Support\Collection;

class CspSetupProcessor
{
    /* @var array */
    protected $config;

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

    public function getSetup(string $config_key): Collection
    {
        $this->config = config($config_key);

        $setupToUse = $this->getSetupToUse();

        $setup = collect($this->config['setups'][$setupToUse]);

        $filteredSetup = $setup->filter(function ($setupPart) {
            return $this->setupPartExists($setupPart);
        });

        $setup = $filteredSetup->map(function ($setupPart) {
            return collect($this->config['setup-parts'][$setupPart]);
        });

        return $setup;
    }
}
