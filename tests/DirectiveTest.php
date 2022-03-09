<?php

use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertTrue;
use Spatie\Csp\Directive;

it('can determine if a directive is valid', function (): void {
    assertTrue(Directive::isValid(Directive::BASE));

    assertFalse(Directive::isValid('invalid'));
});
