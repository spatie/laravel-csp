<?php

namespace Spatie\LaravelCsp\MiddleWare;

use Closure;
use Illuminate\Http\Request;
use Spatie\LaravelCsp\CspHeader;

class CSPHeaderMiddleware extends CspHeader
{
    protected $response;

    public function handle(Request $request, Closure $next)
    {
        $this->response = $next($request);

        $this->addCSPHeaderToResponse();

        return $this->response;
    }
}
