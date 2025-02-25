<?php

it('will output correct nonce', function (): void {
    $nonce = app('csp-nonce');

    $view = app('view')
        ->file(__DIR__.'/../fixtures/view.blade.php')
        ->render();

    expect($view)->toBe('<script nonce="'.$nonce.'"></script>');
});
