<?php

namespace Spatie\LaravelCsp;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Spatie\LaravelCsp\LaravelCspClass
 */
class LaravelCspFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'LaravelCsp';
    }
}
