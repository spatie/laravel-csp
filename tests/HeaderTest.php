<?php

namespace Spatie\LaravelCsp\Tests;

use Spatie\LaravelCsp\Profile\Strict;

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
            'connect-src' => ['self', 'www.google-analytics.com'],
            'form-action' => ['self'],
            'img-src' => ['self'],
            'script-src' => ['self', 'www.google-analytics.com', 'www.googletagmanager.com'],
            'style-src' => ['self', 'fonts.googleapis.com'],
            'media-src' => ['self'],
            'font-src' => ['fonts.gstatic.com'],
            'frame-src' => ['www.youtube.com'],
            'worker-src' => ['www.youtube.com'],
            'child-src' => ['www.youtube.com'],
        ], $csp->profile->toArray());
    }

    /** @test */
    public function it_can_create_the_policy_from_the_default_class()
    {
        $headers = $this->call('get', 'test')->headers->all();


        $this->assertArrayHasKey('content-security-policy', $headers);

        $this->assertEquals(
            'default-src: none; '.
            'connect-src: self; '.
            'form-action: self; '.
            'img-src: self; '.
            'script-src: self; '.
            'style-src: self; '.
            'media-src: self;',
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
            'default-src: none; '.
            'connect-src: self www.google-analytics.com; '.
            'form-action: self; '.
            'img-src: self; '.
            'script-src: self www.google-analytics.com www.googletagmanager.com; '.
            'style-src: self fonts.googleapis.com; '.
            'media-src: self; '.
            'font-src: fonts.gstatic.com; '.
            'child-src: www.youtube.com; '.
            'frame-src: www.youtube.com; '.
            'worker-src: www.youtube.com;',
            $headers['content-security-policy'][0]
        );
    }
}
