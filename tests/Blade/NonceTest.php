<?php

it('will output correct nonce', function (): void {
    $nonce = csp_nonce();

    $view = app('view')
        ->file(__DIR__.'/../fixtures/view.blade.php')
        ->render();

    expect($view)->toBe('<script nonce="'.$nonce.'"></script>');
});
