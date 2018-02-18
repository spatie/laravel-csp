<?php

namespace Spatie\Csp\Tests;

use Spatie\Csp\Directive;
use Spatie\Csp\AddCspHeaders;
use Spatie\Csp\Profiles\Basic;
use Spatie\Csp\Profiles\Profile;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\Facades\Route;
use Spatie\Csp\Exceptions\InvalidCspProfile;
use Symfony\Component\HttpFoundation\HeaderBag;

class GlobalMiddlewareTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        app(Kernel::class)->pushMiddleware(AddCspHeaders::class);

        Route::get('test-route', function () {
            return 'ok';
        });
    }

    /** @test */
    public function the_default_configuration_will_set_csp_headers()
    {
        $headers = $this->getResponseHeaders();

        $this->assertContains("default-src 'self';", $headers->get('Content-Security-Policy'));

        $this->assertNull($headers->get('Content-Security-Policy-Report-Only'));
    }

    /** @test */
    public function it_can_set_reporty_only_csp_headers()
    {
        config([
            'csp.profile' => '',
            'csp.report_only_profile' => Basic::class,
        ]);

        $headers = $this->getResponseHeaders();

        $this->assertContains("default-src 'self';", $headers->get('Content-Security-Policy-Report-Only'));
    }

    /** @test */
    public function it_wont_set_any_headers_if_not_enabled_in_the_config()
    {
        config([
            'csp.enabled' => false,
        ]);

        $headers = $this->getResponseHeaders();

        $this->assertNull($headers->get('Content-Security-Policy'));
    }

    /** @test */
    public function a_report_uri_can_be_set_in_the_config()
    {
        config(['csp.report_uri' => 'https://report-uri.com']);

        $headers = $this->getResponseHeaders();

        $cspHeader = $headers->get('Content-Security-Policy');

        $this->assertContains(
            'report-uri https://report-uri.com',
            $cspHeader
        );

        /*
        $this->assertContains(
            'report-to {"url":"https:\/\/report-uri.com","group-name":"Basic","max-age":18144000};',
            $cspHeader
        );
        */
    }

    /** @test */
    public function using_an_invalid_profile_class_will_throw_an_exception()
    {
        $this->withoutExceptionHandling();

        $invalidProfileClassName = get_class(new class {
        });

        config(['csp.profile' => $invalidProfileClassName]);

        $this->expectException(InvalidCspProfile::class);

        $this->getResponseHeaders();
    }

    /** @test */
    public function it_can_use_multiple_values_for_the_same_directive()
    {
        $profile = new class extends Profile {
            public function configure()
            {
                $this
                    ->addDirective(Directive::FRAME, 'src-1')
                    ->addDirective(Directive::FRAME, 'src-2')
                    ->addDirective(Directive::FORM_ACTION, 'action-1')
                    ->addDirective(Directive::FORM_ACTION, 'action-2');
            }
        };

        config(['csp.profile' => get_class($profile)]);

        $headers = $this->getResponseHeaders();

        $this->assertEquals(
            'frame-src src-1 src-2;form-action action-1 action-2',
            $headers->get('Content-Security-Policy')
        );
    }

    /** @test */
    public function it_can_add_multiple_values_for_the_same_directive_in_one_go()
    {
        $profile = new class extends Profile {
            public function configure()
            {
                $this
                    ->addDirective(Directive::FRAME, ['src-1', 'src-2']);
            }
        };

        config(['csp.profile' => get_class($profile)]);

        $headers = $this->getResponseHeaders();

        $this->assertEquals(
            'frame-src src-1 src-2',
            $headers->get('Content-Security-Policy')
        );
    }

    /** @test */
    public function it_will_automatically_quote_special_directive_values()
    {
        $profile = new class extends Profile {
            public function configure()
            {
                $this->addDirective(Directive::SCRIPT, ['self']);
            }
        };

        config(['csp.profile' => get_class($profile)]);

        $headers = $this->getResponseHeaders();

        $this->assertEquals(
            "script-src 'self'",
            $headers->get('Content-Security-Policy')
        );
    }

    /** @test */
    public function it_will_not_output_the_same_directive_values_twice()
    {
        $profile = new class extends Profile {
            public function configure()
            {
                $this->addDirective(Directive::SCRIPT, ['self', 'self']);
            }
        };

        config(['csp.profile' => get_class($profile)]);

        $headers = $this->getResponseHeaders();

        $this->assertEquals(
            "script-src 'self'",
            $headers->get('Content-Security-Policy')
        );
    }

    /** @test */
    public function route_middleware_will_overwrite_global_middleware_for_that_route()
    {
        $this->withoutExceptionHandling();

        $customProfile = new class extends Profile {
            public function configure()
            {
                $this->addDirective(Directive::BASE, 'custom-profile');
            }
        };

        Route::get('other-route', function () {
            return 'ok';
        })->middleware(AddCspHeaders::class.':'.get_class($customProfile));

        $headers = $this->getResponseHeaders('other-route');

        $this->assertEquals(
            'base-uri custom-profile',
            $headers->get('Content-Security-Policy')
        );
    }

    protected function getResponseHeaders(string $url = 'test-route'): HeaderBag
    {
        return $this
            ->get($url)
            ->assertSuccessful()
            ->headers;
    }
}
