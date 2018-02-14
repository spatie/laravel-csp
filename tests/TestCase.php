<?php

namespace Spatie\LaravelCsp\Tests;

use Spatie\LaravelCsp\CspHeader;
use Spatie\LaravelCsp\CspServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    /** @var array */
    protected $config = [];

    public function setUp()
    {
        parent::setUp();

        $this->setupDummyRoutes();
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.key', '6rE9Nz59bGRbeMATftriyQjrpF7DcOQm');

        $app['config']->set('csp.enabled', true);
    }

    protected function getPackageProviders($app)
    {
        return [
            CspServiceProvider::class,
        ];
    }

    public function setupDummyRoutes()
    {
        $this->app['router']->group(
            ['middleware' => CspHeader::class],
            function () {
                $this->app['router']->get('test', function () {
                    return 'Hello world!';
                });
            }
        );
    }
}
