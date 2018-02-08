<?php

namespace Spatie\LaravelCsp\Tests;

use Illuminate\Routing\RouteCollection;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\LaravelCsp\LaravelCspServiceProvider;
use Spatie\LaravelCsp\MiddleWare\CSPHeaderMiddleware;

class TestCase extends Orchestra
{
    /** @var array */
    protected $config = [];

    public function setUp()
    {
        parent::setUp();

//        $this->registerMiddleWare();

        $this->setupDummyRoutes();

        $this->config = $this->app['config']->get('csp');

//        $this->setupRoutes($this->app);
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.key', '6rE9Nz59bGRbeMATftriyQjrpF7DcOQm');

        $app['config']->set('csp.default', 'strict');
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelCspServiceProvider::class,
        ];
    }

    protected function registerMiddleware()
    {
        $this->app[Router::class]->aliasMiddleware('csp', CSPHeaderMiddleware::class);
    }

    public function setupDummyRoutes()
    {
        $this->app['router']->group(
            ['middleware' => CSPHeaderMiddleware::class],
            function () {
                $this->app['router']->get('test', function () {
                    return 'Hello world!';
                });
            }
        );
    }
}
