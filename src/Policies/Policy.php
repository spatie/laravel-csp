<?php

namespace Spatie\Csp\Policies;

use Spatie\Csp\Directive;
use Illuminate\Http\Request;
use Spatie\Csp\Exceptions\InvalidDirective;
use Symfony\Component\HttpFoundation\Response;

abstract class Policy
{
    protected $directives = [];

    protected $reportOnly = false;

    abstract public function configure();

    /**
     * @param string $directive
     * @param string|array $values
     *
     * @return \Spatie\Csp\Policies\Policy
     *
     * @throws \Spatie\Csp\Exceptions\InvalidDirective
     */
    public function addDirective(string $directive, $values): self
    {
        $this->guardAgainstInvalidDirectives($directive);

        foreach (array_wrap($values) as $value) {
            $sanitizedValue = $this->sanitizeValue($value);

            if (! in_array($sanitizedValue, $this->directives[$directive] ?? [])) {
                $this->directives[$directive][] = $sanitizedValue;
            }
        }

        return $this;
    }

    public function reportOnly(): self
    {
        $this->reportOnly = true;

        return $this;
    }

    public function enforce(): self
    {
        $this->reportOnly = false;

        return $this;
    }

    public function reportTo(string $uri): self
    {
        $this->directives['report-uri'] = [$uri];

        return $this;
    }

    public function shouldBeApplied(Request $request, Response $response): bool
    {
        return config('csp.enabled');
    }

    public function addNonceForDirective(string $directive): self
    {
        return $this->addDirective($directive, "'nonce-".app('csp-nonce')."'");
    }

    /**
     * @param string|array $values
     *
     * @return \Spatie\Csp\Policies\Policy
     *
     * @throws \Spatie\Csp\Exceptions\InvalidDirective
     */
    public function addFrame($values): self
    {
        return $this
            ->addDirective(Directive::WORKER, $values)
            ->addDirective(Directive::CHILD, $values)
            ->addDirective(Directive::FRAME, $values);
    }

    public function applyTo(Response $response)
    {
        $this->configure();

        $headerName = $this->reportOnly
            ? 'Content-Security-Policy-Report-Only'
            : 'Content-Security-Policy';

        if ($response->headers->has($headerName)) {
            return;
        }

        $response->headers->set($headerName, (string) $this);
    }

    public function __toString()
    {
        return collect($this->directives)
            ->map(function (array $values, string $directive) {
                $valueString = implode(' ', $values);

                return "{$directive} {$valueString}";
            })
            ->implode(';');
    }

    protected function guardAgainstInvalidDirectives(string $directive)
    {
        if (! Directive::isValid($directive)) {
            throw InvalidDirective::notSupported($directive);
        }
    }

    protected function sanitizeValue(string $value): string
    {
        $specialDirectiveValues = [
            'none',
            'report-sample',
            'self',
            'strict-dynamic',
            'unsafe-eval',
            'unsafe-inline',
        ];

        if (in_array($value, $specialDirectiveValues)) {
            return "'{$value}'";
        }

        return $value;
    }
}
