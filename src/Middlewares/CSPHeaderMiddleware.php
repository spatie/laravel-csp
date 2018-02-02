<?php

namespace Spatie\LaravelCsp\Middlewares;

use Closure;
use Spatie\LaravelCsp\CspHeader;

class CSPHeaderMiddleware extends CspHeader
{
    public function handle($request, Closure $next)
    {
        return $this->addCSPHeaderToResponse($next($request));
    }
}
