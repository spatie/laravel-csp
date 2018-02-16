<?php

namespace Spatie\LaravelCsp\Tests;

use Spatie\LaravelCsp\Csp;
use Spatie\LaravelCsp\Profiles\Strict;
use Spatie\LaravelCsp\Exceptions\InvalidDirective;

class HeaderTest extends TestCase
{
    /** @test */
    public function it_sets_a_default_csp_header_to_a_web_request()
    {
        $headers = $this->call('get', 'test')->headers->all();

        $this->assertArrayHasKey('content-security-policy', $headers);
    }

    /** @test */
    public function it_can_get_the_header_collection_from_the_default_Strict_class()
    {
        $csp = new Strict();

        $csp->profileSetup();

        $this->assertEquals([
            'default-src' => ['none'],
            'connect-src' => ['self'],
            'form-action' => ['self'],
            'img-src' => ['self'],
            'script-src' => ['self'],
            'style-src' => ['self'],
            'media-src' => ['self'],
        ], $csp->profile->toArray());
    }

    /** @test */
    public function it_can_use_a_custom_class_to_generate_a_header_collection()
    {
        $csp = new CustomTestSetup();

        $csp->profileSetup();

        $this->assertEquals([
            'default-src' => ['none'],
            'connect-src' => ['self', 'https://www.google-analytics.com'],
            'form-action' => ['self'],
            'img-src' => ['self'],
            'script-src' => ['self', 'https://www.google-analytics.com', 'https://www.googletagmanager.com'],
            'style-src' => ['self', 'https://fonts.googleapis.com'],
            'media-src' => ['self'],
            'font-src' => ['https://fonts.gstatic.com'],
            'frame-src' => ['https://www.youtube.com'],
            'worker-src' => ['https://www.youtube.com'],
            'child-src' => ['https://www.youtube.com'],
        ], $csp->profile->toArray());
    }

    /** @test */
    public function it_can_create_the_policy_from_the_default_class()
    {
        $headers = $this->call('get', 'test')->headers->all();

        $this->assertArrayHasKey('content-security-policy', $headers);

        $this->assertEquals(
            "default-src 'none'; ".
            "connect-src 'self'; ".
            "form-action 'self'; ".
            "img-src 'self'; ".
            "script-src 'self'; ".
            "style-src 'self'; ".
            "media-src 'self';",
            $headers['content-security-policy'][0]
        );
    }

    /** @test */
    public function it_can_create_the_policy_from_a_custom_class()
    {
        $this->app['config']->set('csp.csp_profile', '\Spatie\LaravelCsp\Tests\CustomTestSetup');

        $headers = $this->call('get', 'test')->headers->all();

        $this->assertArrayHasKey('content-security-policy', $headers);

        $this->assertEquals(
            "default-src 'none'; ".
            "connect-src 'self' https://www.google-analytics.com; ".
            "form-action 'self'; ".
            "img-src 'self'; ".
            "script-src 'self' https://www.google-analytics.com https://www.googletagmanager.com; ".
            "style-src 'self' https://fonts.googleapis.com; ".
            "media-src 'self'; ".
            "font-src https://fonts.gstatic.com; ".
            "child-src https://www.youtube.com; ".
            "frame-src https://www.youtube.com; ".
            "worker-src https://www.youtube.com;",
            $headers['content-security-policy'][0]
        );
    }

    /** @test */
    public function it_can_handle_all_the_allows()
    {
        $this->app['config']->set('csp.csp_profile', '\Spatie\LaravelCsp\Tests\AllAllowsTest');

        $headers = $this->call('get', 'test')->headers->all();

        $this->assertArrayHasKey('content-security-policy', $headers);

        $this->assertEquals(
            "default-src 'none'; ".
            "connect-src 'self' https://www.google-analytics.com https://*.pusher.com https://ajax.googleapis.com https://query.yahooapis.com; ".
            "form-action 'self'; ".
            "img-src 'self'; ".
            "script-src 'self' https://www.google-analytics.com https://www.googletagmanager.com https://stats.pusher.com; ".
            "style-src 'self' https://fonts.googleapis.com https://use.fontawesome.com; ".
            "media-src 'self'; ".
            "font-src data: https://fonts.gstatic.com https://use.fontawesome.com; ".
            "child-src https://www.youtube.com https://codepen.io https://application/x-java-applet; ".
            "frame-src https://www.youtube.com https://codepen.io; ".
            "worker-src https://www.youtube.com https://codepen.io; ".
            "plugin-types https://application/pdf;",
            $headers['content-security-policy'][0]
        );
    }

    /** @test */
    public function it_can_throw_an_exception_when_trying_to_add_a_directive_that_is_not_provided()
    {
        $profile = new Csp();

        $this->expectException(InvalidDirective::class);

        $profile->addHeader('wrong-directive', 'self');
    }
}
