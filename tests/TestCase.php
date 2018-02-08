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

    public function setupRoutes($app)
    {
        $app['router']->setRoutes(new RouteCollection());

        Route::any('/', ['middleware' => 'csp', function () {
            return 'secret content';
        }]);
    }

    public function setupDummyRoutes(){
        $this->app['router']->group(
            ['middleware' => CSPHeaderMiddleware::class],
            function () {
                $this->app['router']->get('test', function () {
                    return 'Hello world!';
                });
                $this->app['router']->get('behold-me', function () {
                    return 'Hello world!';
                });
                $this->app['router']->get('go-away', function () {
                    return 'Hello world!';
                });
                $this->app['router']->get('dont-follow-me', function () {
                    return response('Hello world!')->header('x-robots-tag', 'nofollow');
                });
            }
        );
    }
}
