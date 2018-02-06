<?php

namespace Spatie\LaravelCsp\Tests;

use Illuminate\Routing\RouteCollection;
use Illuminate\Routing\Router;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\LaravelCsp\LaravelCspServiceProvider;
use Spatie\LaravelCsp\Middlewares\CSPHeaderMiddleware;

class TestCase extends Orchestra
{
    /** @var array */
    protected $config = [];

    public function setUp()
    {
        parent::setUp();

        $this->registerMiddleWare();

        $this->config = $this->app['config']->get('csp');

        $this->setupRoutes($this->app);
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.key', '6rE9Nz59bGRbeMATftriyQjrpF7DcOQm');

        $app['config']->set('csp.default', 'strict');
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            LaravelCspServiceProvider::class,
        ];
    }

    protected function registerMiddleware()
    {
        $this->app[Router::class]->aliasMiddleware('web', CSPHeaderMiddleware::class);
    }

    function setupRoutes($app)
    {
        $this->app->get('router')->setRoutes(new RouteCollection());

        Route::any('/secret-page', ['middleware' => 'web', function () {
            return 'secret content';
        }]);
    }
}
