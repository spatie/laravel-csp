<?php

use function Orchestra\Testbench\artisan;
use Spatie\Csp\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

// make sure view is compiled fresh each run in blade tests
uses()->beforeEach(fn () => artisan($this, 'view:clear'))->in('Blade');

expect()->extend('toHaveMetaContent', function ($value) {
    return expect($this->value)
        ->toMatch('/<meta http-equiv="[\w-]+" content="' . preg_quote($value) . '">/');
});

function withoutExceptionHandling(): void
{
    test()->withoutExceptionHandling();
}
