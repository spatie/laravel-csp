<?php

namespace Spatie\LaravelCsp\Tests;

use Spatie\LaravelCsp\Profile\Strict;

class HeaderTest extends TestCase
{
    //** @test */
    public function it_can_process_a_setup()
    {
        // strict setup
        $this->app['config']->set('csp.default', 'strict');

        $setupCollection = (new CspSetupProcessor())->getSetup('csp');

        $this->assertEquals(
            [
                [
                    'default-src' => ['none'],
                    'connect-src' => ['self'],
                    'form-action' => ['self'],
                    'img-src' => ['self'],
                    'script-src' => ['self'],
                    'style-src' => ['self'],
                    'media-src' => ['self'],
                ],
            ],
            $setupCollection->toArray()
        );

        // basic setup
        $this->app['config']->set('csp.default', 'basic');

        $setupCollection = (new CspSetupProcessor())->getSetup('csp');

        $this->assertEquals(
            [
                [
                    'default-src' => ['none'],
                    'connect-src' => ['self'],
                    'form-action' => ['self'],
                    'img-src' => ['self'],
                    'script-src' => ['self'],
                    'style-src' => ['self'],
                    'media-src' => ['self'],
                ],
                [
                    'connect-src' => ['www.google-analytics.com'],
                    'script-src' => ['www.google-analytics.com', 'www.googletagmanager.com'],
                    'img-src' => ['www.google-analytics.com'],
                ],
                [
                    'font-src' => ['fonts.gstatic.com'],
                    'style-src' => ['fonts.googleapis.com'],
                ],
                [
                    'frame-src' => ['www.youtube.com'],
                    'worker-src' => ['codepen.io'],
                    'child-src' => ['codepen.io'],
                ],
            ],
            $setupCollection->toArray()
        );
    }

    //** @test */
    public function it_can_fabricate_a_policy_from_a_setup()
    {
        // strict setup
        $this->app['config']->set('csp.default', 'strict');

        $setupCollection = (new CspSetupProcessor())->getSetup('csp');

        $policy = (new CspPolicyFactory())->create($setupCollection);

        $this->assertEquals(
            'default-src: none; '.
            'connect-src: self; '.
            'form-action: self; '.
            'img-src: self; '.
            'script-src: self; '.
            'style-src: self; '.
            'media-src: self;',
            $policy
        );

        // basic setup
        $this->app['config']->set('csp.default', 'basic');

        $setupCollection = (new CspSetupProcessor())->getSetup('csp');

        $policy = (new CspPolicyFactory())->create($setupCollection);

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
            $policy
        );
    }

    //** @test */
    public function it_sets_a_default_csp_header_to_a_web_request()
    {
        // strict setup
        $this->app['config']->set('csp.default', 'strict');

        $headers = $this->call('get', 'test')->headers->all();

        $this->assertArrayHasKey('content-security-policy', $headers);

        // basic setup
        $this->app['config']->set('csp.default', 'basic');

        $headers = $this->call('get', 'test')->headers->all();

        $this->assertArrayHasKey('content-security-policy', $headers);
    }

//    /** @test */
//    public function it_can_get_the_content_from_the_config_into_the_header_correctly()
//    {
//        // strict setup
//        $this->app['config']->set('csp.default', 'strict');
//
//        $headers = $this->call('get', 'test')->headers->all();
//
//        $this->assertEquals(
//            'default-src: none; '.
//            'connect-src: self; '.
//            'form-action: self; '.
//            'img-src: self; '.
//            'script-src: self; '.
//            'style-src: self; '.
//            'media-src: self;',
//            $headers['content-security-policy'][0]
//        );
//
//        // basic setup
//        $this->app['config']->set('csp.default', 'basic');
//
//        $headers = $this->call('get', 'test')->headers->all();
//
//        $this->assertEquals(
//            'default-src: none; '.
//            'connect-src: self www.google-analytics.com; '.
//            'form-action: self; '.
//            'img-src: self www.google-analytics.com; '.
//            'script-src: self www.google-analytics.com www.googletagmanager.com; '.
//            'style-src: self fonts.googleapis.com; '.
//            'media-src: self; '.
//            'font-src: fonts.gstatic.com; '.
//            'frame-src: www.youtube.com; '.
//            'worker-src: codepen.io; '.
//            'child-src: codepen.io;',
//            $headers['content-security-policy'][0]
//        );
//    }

    /** @test */
    public function it_can_get_the_header_collection_from_the_default_Strict_class()
    {
        $csp = new Strict();

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

    //** @test */
    public function it_can_get_the_policy_from_the_default_class()
    {
    }
}
