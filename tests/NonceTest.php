<?php

namespace Spatie\Csp\Tests;

use Illuminate\Support\Facades\Artisan;

class NonceTest extends TestCase
{
    /** @test */
    public function calling_the_nonce_function_will_generate_the_same_result()
    {
        $nonce = cspNonce();

        $this->assertEquals(strlen($nonce), 32);

        foreach (range(1, 5) as $i) {
            $this->assertEquals($nonce, cspNonce());
        }
    }

    /** @test */
    public function it_can_use_the_blade_directive()
    {
        $generatedNonce = cspNonce();

        $this->assertEquals(
            "<script nonce='{$generatedNonce}' type=\"text/javascript\"></script>",
            $this->renderView('scriptNonce')
        );

        $this->assertEquals(
            "<style nonce='{$generatedNonce}'></style>",
            $this->renderView('styleNonce')
        );
    }

    protected function renderView($view)
    {
        Artisan::call('view:clear');

        if (is_string($view)) {
            $view = view($view);
        }

        return trim((string)($view));
    }
}
