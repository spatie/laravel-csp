<?php

namespace Spatie\Csp\Tests;

use Illuminate\Support\Facades\Route;
use Spatie\Csp\AddCspHeaders;
use Spatie\Csp\Exceptions\InvalidCspProfile;
use Symfony\Component\HttpFoundation\HeaderBag;

class AddCspHeadersTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        Route::get('test', function () {
            return 'ok';
        })->middleware(AddCspHeaders::class);
    }

    /** @test */
    public function it_can_set_the_basic_csp_headers()
    {
        $headers = $this->getResponseHeaders();

        $this->assertContains("default-src 'self';", $headers->get('Content-Security-Policy'));
    }

    /** @test */
    public function it_wont_set_any_headers_if_not_enabled_in_the_config()
    {
        config(['csp.enabled' => false]);

        $headers = $this->getResponseHeaders();

        $this->assertNull($headers->get('Content-Security-Policy'));
    }

    /** @test */
    public function it_can_be_set_in_report_only_mode_via_the_config()
    {
        config(['csp.report_only' => true]);

        $headers = $this->getResponseHeaders();

        $this->assertNotNull($headers->get('Content-Security-Policy-Report-Only'));

        $this->assertNull($headers->get('Content-Security-Policy'));
    }

    /** @test */
    public function a_report_uri_can_be_set_in_the_config()
    {
        config(['csp.report_uri' => 'https://report-uri.com']);

        $headers = $this->getResponseHeaders();

        $this
            ->assertCspHeaderContains($headers, 'report-uri https://report-uri.com;')
            ->assertCspHeaderContains($headers, 'report-to {"url":"https:\/\/report-uri.com","group-name":"Basic","max-age":18144000};');
    }

    /** @test */
    public function using_an_invalid_profile_class_will_throw_an_exception()
    {
        $this->withoutExceptionHandling();

        $invalidProfileClassName = get_class(new class {});

        config(['csp.profile' => $invalidProfileClassName]);

        $this->expectException(InvalidCspProfile::class);

        $this->getResponseHeaders();
    }

    protected function assertCspHeaderContains(HeaderBag $headerBag, string $needle): self
    {
        $this->assertContains($needle, $headerBag->get('Content-Security-Policy'));

        return $this;
    }

    protected function getResponseHeaders(): HeaderBag
    {
        return $this
            ->get('test')
            ->assertSuccessful()
            ->headers;
    }
}