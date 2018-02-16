<?php

namespace Spatie\Csp\Tests;

use Spatie\Csp\Csp;
use Spatie\Csp\AddCspHeaders;
use Spatie\Csp\CspServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            CspServiceProvider::class,
        ];
    }
}
