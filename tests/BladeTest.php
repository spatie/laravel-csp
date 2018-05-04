<?php

namespace Spatie\Csp\Tests;

class BladeTest extends TestCase
{
    /** @test */
    public function blade_directive_outputs_correct_nonce()
    {
        // make sure view is compiled fresh each run
        $this->artisan('view:clear');

        $nonce = csp_nonce();

        $view = app('view')
            ->file(__DIR__. '/fixtures/view.blade.php')
            ->render();

        $this->assertEquals('<script nonce="' . $nonce . '"></script>', $view);
    }
}
