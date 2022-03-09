<?php

use function PHPUnit\Framework\assertEquals;

it('will generate the same result', function (): void {
    $nonce = csp_nonce();

    assertEquals(strlen($nonce), 32);

    foreach (range(1, 5) as $i) {
        assertEquals($nonce, csp_nonce());
    }
});
