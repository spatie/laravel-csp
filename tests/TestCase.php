<?php

namespace Spatie\Csp\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\Csp\CspServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            CspServiceProvider::class,
        ];
    }
}
