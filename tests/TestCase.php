<?php

namespace Spatie\Csp\Tests;

use Spatie\Csp\Csp;
use Spatie\Csp\CspServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            CspServiceProvider::class,
        ];
    }
}
