<?php

namespace Spatie\LaravelCsp\Tests;

use Illuminate\Routing\RouteCollection;
use Illuminate\Support\Facades\Route;

class HeaderTest extends TestCase
{
    protected $headers;

    public function setUp()
    {
        parent::setUp();

        $this->headers = $this->call('get', 'test')->headers->all();
    }

    /** @test */
    public function it_sets_a_default_csp_header_to_a_web_request()
    {
        $this->assertArrayHasKey('content-security-policy', $this->headers);
    }

    /** @test */
    public function it_can_get_the_content_from_the_config_into_the_header_correctly()
    {
        $this->assertEquals('none self self self self self', $this->headers['content-security-policy'][0]);
    }
}
