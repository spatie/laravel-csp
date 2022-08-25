<?php

namespace Spatie\Csp\Policies;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use ReflectionClass;
use Spatie\Csp\Directive;
use Spatie\Csp\Exceptions\InvalidDirective;
use Spatie\Csp\Exceptions\InvalidValueSet;
use Spatie\Csp\Keyword;
use Spatie\Csp\Value;
use Symfony\Component\HttpFoundation\Response;

abstract class Policy
{
    protected array $directives = [];

    protected bool $reportOnly = false;

    abstract public function configure();

    public function addDirective(string $directive, string|array|bool $values): self
    {
        $this->guardAgainstInvalidDirectives($directive);
        $this->guardAgainstInvalidValues(Arr::wrap($values));

        if ($values === Value::NO_VALUE) {
            $this->directives[$directive][] = Value::NO_VALUE;

            return $this;
        }

        $values = array_filter(
            Arr::flatten(
                array_map(fn ($value) => explode(' ', $value), Arr::wrap($values))
            )
        );

        if (in_array(Keyword::NONE, $values, true)) {
            $this->directives[$directive] = [$this->sanitizeValue(Keyword::NONE)];

            return $this;
        }

        $this->directives[$directive] = array_filter($this->directives[$directive] ?? [], function ($value) {
            return $value !== $this->sanitizeValue(Keyword::NONE);
        });

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

    public function prepareHeader(): string
    {
        $this->configure();

        return $this->reportOnly
            ? 'Content-Security-Policy-Report-Only'
            : 'Content-Security-Policy';
    }

    public function applyTo(Response $response)
    {
        $headerName = $this->prepareHeader();

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

    protected function guardAgainstInvalidValues(array $values)
    {
        if (in_array(Keyword::NONE, $values, true) && count($values) > 1) {
            throw InvalidValueSet::noneMustBeOnlyValue();
        }
    }

    protected function isHash(string $value): bool
    {
        $acceptableHashingAlgorithms = [
            'sha256-',
            'sha384-',
            'sha512-',
        ];

        return Str::startsWith($value, $acceptableHashingAlgorithms);
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
