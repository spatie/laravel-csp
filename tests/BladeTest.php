<?php

use function Orchestra\Testbench\artisan;
use function PHPUnit\Framework\assertEquals;

it('will output correct nonce', function (): void {
    // make sure view is compiled fresh each run
    artisan($this, 'view:clear');

    $nonce = csp_nonce();

    $view = app('view')
        ->file(__DIR__.'/fixtures/view.blade.php')
        ->render();

    assertEquals('<script nonce="'.$nonce.'"></script>', $view);
});
