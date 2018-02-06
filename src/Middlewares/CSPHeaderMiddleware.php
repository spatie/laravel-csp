<?php

namespace Spatie\LaravelCsp\Middlewares;

use Closure;
use http\Env\Request;
use http\Env\Response;
use Spatie\LaravelCsp\CspHeader;

class CSPHeaderMiddleware extends CspHeader
{
    public function handle(Request $request, Closure $next): Response
    {
        return $this->addCSPHeaderToResponse($next($request));
    }
}
