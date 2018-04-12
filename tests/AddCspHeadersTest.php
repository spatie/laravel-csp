<?php

namespace Spatie\Csp\Tests;

use Spatie\Csp\Directive;
use Spatie\Csp\AddCspHeaders;
use Spatie\Csp\Policies\Basic;
use Spatie\Csp\Policies\Policy;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\Facades\Route;
use Spatie\Csp\Exceptions\InvalidCspPolicy;
use Spatie\Csp\Value;
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
    public function it_can_set_report_only_csp_headers()
    {
        config([
            'csp.policy' => '',
            'csp.report_only_policy' => Basic::class,
        ]);

        $headers = $this->getResponseHeaders();

        $this->assertNull($headers->get('Content-Security-Policy'));
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
    }

    /** @test */
    public function using_an_invalid_policy_class_will_throw_an_exception()
    {
        $this->withoutExceptionHandling();

        $invalidPolicyClassName = get_class(new class {
        });

        config(['csp.policy' => $invalidPolicyClassName]);

        $this->expectException(InvalidCspPolicy::class);

        $this->getResponseHeaders();
    }

    /** @test */
    public function it_can_use_multiple_values_for_the_same_directive()
    {
        $policy = new class extends Policy {
            public function configure()
            {
                $this
                    ->addDirective(Directive::FRAME, 'src-1')
                    ->addDirective(Directive::FRAME, 'src-2')
                    ->addDirective(Directive::FORM_ACTION, 'action-1')
                    ->addDirective(Directive::FORM_ACTION, 'action-2');
            }
        };

        config(['csp.policy' => get_class($policy)]);

        $headers = $this->getResponseHeaders();

        $this->assertEquals(
            'frame-src src-1 src-2;form-action action-1 action-2',
            $headers->get('Content-Security-Policy')
        );
    }

    /** @test */
    public function a_policy_can_be_put_in_report_only_mode()
    {
        $policy = new class extends Policy {
            public function configure()
            {
                $this->reportOnly();
            }
        };

        config(['csp.policy' => get_class($policy)]);

        $headers = $this->getResponseHeaders();

        $this->assertNull($headers->get('Content-Security-Policy'));
        $this->assertNotNull($headers->get('Content-Security-Policy-Report-Only'));
    }

    /** @test */
    public function it_can_add_multiple_values_for_the_same_directive_in_one_go()
    {
        $policy = new class extends Policy {
            public function configure()
            {
                $this
                    ->addDirective(Directive::FRAME, ['src-1', 'src-2']);
            }
        };

        config(['csp.policy' => get_class($policy)]);

        $headers = $this->getResponseHeaders();

        $this->assertEquals(
            'frame-src src-1 src-2',
            $headers->get('Content-Security-Policy')
        );
    }

    /** @test */
    public function it_will_automatically_quote_special_directive_values()
    {
        $policy = new class extends Policy {
            public function configure()
            {
                $this->addDirective(Directive::SCRIPT, [Value::SELF]);
            }
        };

        config(['csp.policy' => get_class($policy)]);

        $headers = $this->getResponseHeaders();

        $this->assertEquals(
            "script-src 'self'",
            $headers->get('Content-Security-Policy')
        );
    }

    /** @test */
    public function it_will_not_output_the_same_directive_values_twice()
    {
        $policy = new class extends Policy {
            public function configure()
            {
                $this->addDirective(Directive::SCRIPT, [Value::SELF, Value::SELF]);
            }
        };

        config(['csp.policy' => get_class($policy)]);

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

        $customPolicy = new class extends Policy {
            public function configure()
            {
                $this->addDirective(Directive::BASE, 'custom-policy');
            }
        };

        Route::get('other-route', function () {
            return 'ok';
        })->middleware(AddCspHeaders::class.':'.get_class($customPolicy));

        $headers = $this->getResponseHeaders('other-route');

        $this->assertEquals(
            'base-uri custom-policy',
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
