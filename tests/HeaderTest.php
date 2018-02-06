<?php

namespace Spatie\LaravelCsp\Tests;

use Illuminate\Routing\RouteCollection;
use Illuminate\Support\Facades\Route;

class HeaderTest extends TestCase
{
    /** @test */
    public function it_sets_a_default_csp_header_to_a_web_request()
    {

        $this->app['config']->set('csp.default', 'strict');

        $this->get('/')->assertHeader('Content-Security-Policy', '');
    }
}
