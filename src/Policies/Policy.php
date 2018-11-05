<?php

namespace Spatie\Csp\Policies;

use ReflectionClass;
use Spatie\Csp\Value;
use Spatie\Csp\Keyword;
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
     * @param string|array|bool $values
     *
     * @return \Spatie\Csp\Policies\Policy
     *
     * @throws \Spatie\Csp\Exceptions\InvalidDirective
     */
    public function addDirective(string $directive, $values): self
    {
        $this->guardAgainstInvalidDirectives($directive);

        if ($values === Value::NO_VALUE) {
            $this->directives[$directive][] = Value::NO_VALUE;

            return $this;
        }

        $values = array_filter(array_flatten(array_map(function ($value) {
            return explode(' ', $value);
        }, array_wrap($values))));

        foreach ($values as $value) {
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

                return empty($valueString) ? "{$directive}" : "{$directive} {$valueString}";
            })
            ->implode(';');
    }

    protected function guardAgainstInvalidDirectives(string $directive)
    {
        if (! Directive::isValid($directive)) {
            throw InvalidDirective::notSupported($directive);
        }
    }

    protected function isHash(string $value): bool
    {
        $acceptableHashingAlgorithms = [
          'sha256-',
          'sha384-',
          'sha512-',
        ];

        return starts_with($value, $acceptableHashingAlgorithms);
    }

    protected function isKeyword(string $value): bool
    {
        $keywords = (new ReflectionClass(Keyword::class))->getConstants();

        return in_array($value, $keywords);
    }

    protected function sanitizeValue(string $value): string
    {
        if (
            $this->isKeyword($value)
            || $this->isHash($value)
        ) {
            return "'{$value}'";
        }

        return $value;
    }
}
