<?php

use Spatie\Csp\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

function withoutExceptionHandling(): void
{
    test()->withoutExceptionHandling();
}
