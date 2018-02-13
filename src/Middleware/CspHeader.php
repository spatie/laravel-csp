<?php

namespace Spatie\LaravelCsp\MiddleWare;

use Closure;
use Illuminate\Http\Request;
use Spatie\LaravelCsp\CspHeaderSetter;

class CspHeader extends CspHeaderSetter
{
    protected $response;

    public function handle(Request $request, Closure $next)
    {
        $this->response = $next($request);

        if (config('csp.enabled')) {
            $this->addCspHeaderToResponse();
        }

        return $this->response;
    }
}
