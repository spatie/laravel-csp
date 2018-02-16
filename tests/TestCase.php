<?php

namespace Spatie\Csp\Tests;

use Spatie\Csp\Csp;
use Spatie\Csp\AddCspHeaders;
use Spatie\Csp\CspServiceProvider;
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

//        $app['config']->set('csp.csp_profile', '\Spatie\Csp\Tests\InvalidCspProfile');
    }

    protected function getPackageAliases($app)
    {
        return [
            'Csp' => Csp::class,
        ];
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
            ['middleware' => AddCspHeaders::class],
            function () {
                $this->app['router']->get('test', function () {
                    return 'Hello world!';
                });
            }
        );
    }
}
