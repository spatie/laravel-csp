<?php

namespace Spatie\Csp;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AddCspHeaders
{
    public function handle(
        Request $request,
        Closure $next,
        ?string $customPreset = null
    ) {
        $response = $next($request);

        if (! config('csp.enabled')) {
            return $response;
        }

        // Ensure custom CSP middleware registered later in the stack gets precedence
        if ($this->hasCspHeader($response)) {
            return $response;
        }

        if ($customPreset) {
            $policy = Policy::create([$customPreset]);

            $response->headers->set('Content-Security-Policy', $policy->getContents());

            return $response;
        }

        $policy = Policy::create(
            presets: config('csp.presets'),
            directives: config('csp.directives'),
            reportUri: config('csp.report_uri'),
        );

        if (! $policy->isEmpty()) {
            $response->headers->set('Content-Security-Policy', $policy->getContents());
        }

        $reportOnlyPolicy = Policy::create(
            presets: config('csp.report_only_presets'),
            directives: config('csp.report_only_directives'),
            reportUri: config('report_uri'),
        );

        if (! $reportOnlyPolicy->isEmpty()) {
            $response->headers->set('Content-Security-Policy-Report-Only', $reportOnlyPolicy->getContents());
        }

        return $response;
    }

    public function hasCspHeader(mixed $response): bool
    {
        if (! $response instanceof Response) {
            return false;
        }

        return $response->headers->has('Content-Security-Policy')
            || $response->headers->has('Content-Security-Policy-Report-Only');
    }
}
