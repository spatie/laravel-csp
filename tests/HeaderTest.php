<?php

namespace Spatie\LaravelCsp\Tests;

use Illuminate\Routing\RouteCollection;
use Illuminate\Support\Facades\Route;

class HeaderTest extends TestCase
{
    /** @test */
    public function it_sets_a_default_csp_header_to_a_web_request()
    {
//        $this->app['config']->set('csp.default', 'strict');

        $headers = $this->call('get', 'test')->headers->all();

        $this->assertArrayHasKey('content-security-policy', $headers);
    }

    /** @test */
    public function it_can_get_the_content_from_the_config_into_the_header_correctly()
    {
        $this->app['config']->set('csp.default', 'basic');

        $headers = $this->call('get', 'test')->headers->all();

        $this->assertEquals(
            'default-src: none; '.
            'connect-src: self www.google-analytics.com; '.
            'form-action: self; '.
            'img-src: self www.google-analytics.com; '.
            'script-src: self www.google-analytics.com www.googletagmanager.com; '.
            'style-src: self fonts.googleapis.com; '.
            'media-src: self; '.
            'font-src: fonts.gstatic.com; '.
            'frame-src: www.youtube.com; '.
            'worker-src: codepen.io; '.
            'child-src: codepen.io;',
            $headers['content-security-policy'][0]
        );
    }
}
