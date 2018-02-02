<?php

namespace Spatie\LaravelCsp\Test;

class ExampleTest extends TestCase
{
    /** @test */
    public function it_sets_a_default_csp_header_to_a_web_request()
    {
//        $this->app['config']->set('csp.', '');
        $response = $this->get('/');
        $response->assertHeader('Content-Security-Policy', '');
    }
}
