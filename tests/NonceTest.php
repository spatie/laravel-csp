<?php

use function PHPUnit\Framework\assertEquals;

it('will generate the same result', function (): void {
    $nonce = app('csp-nonce');

    assertEquals(strlen($nonce), 32);

    foreach (range(1, 5) as $i) {
        assertEquals($nonce, app('csp-nonce'));
    }
});
