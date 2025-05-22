<?php

namespace Spatie\Csp;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use ReflectionClass;
use Spatie\Csp\Exceptions\InvalidDirective;
use Spatie\Csp\Exceptions\InvalidPreset;
use Spatie\Csp\Exceptions\InvalidValueSet;

class Policy
{
    protected array $directives = [];

    public function __construct(
        protected ?string $reportUri = null,
    ) {
    }

    public function add(Directive|array $directives, Keyword|string|array|bool $values): self
    {
        foreach (Arr::wrap($directives) as $directive) {
            /** @var Directive $directive */

            $this->guardAgainstInvalidDirectives($directive);
            $this->guardAgainstInvalidValues(Arr::wrap($values));

            if ($values === Value::NO_VALUE) {
                $this->directives[$directive->value][] = Value::NO_VALUE;

                return $this;
            }

            $values = array_filter(
                Arr::flatten(
                    array_map(function (Keyword|string $value) {
                        return $value instanceof Keyword ? $value : explode(' ', $value);
                    }, array_filter(Arr::wrap($values)))
                )
            );

            if (in_array(Keyword::NONE, $values, true)) {
                $this->directives[$directive->value] = [$this->sanitizeValue(Keyword::NONE)];

                return $this;
            }

            $this->directives[$directive->value] = array_filter($this->directives[$directive->value] ?? [], function ($value) {
                return $value !== $this->sanitizeValue(Keyword::NONE);
            });

            foreach ($values as $value) {
                $sanitizedValue = $this->sanitizeValue($value);

                if (! in_array($sanitizedValue, $this->directives[$directive->value] ?? [])) {
                    $this->directives[$directive->value][] = $sanitizedValue;
                }
            }
        }

        return $this;
    }

    public function addNonce(Directive $directive): self
    {
        if (! config('csp.nonce_enabled', true)) {
            return $this;
        }

        $nonce = app('csp-nonce');
        if (empty($nonce)) {
            return $this;
        }

        return $this->add($directive, "'nonce-{$nonce}'");
    }

    public function setReportUri(string $reportUri): self
    {
        $this->reportUri = $reportUri;

        return $this;
    }

    public function isEmpty(): bool
    {
        return empty($this->directives);
    }

    public function getContents(): string
    {
        $directives = $this->directives;

        if ($this->reportUri) {
            $directives[Directive::REPORT->value] = [$this->reportUri];
        }

        return collect($directives)
            ->map(function (array $values, string $directive) {
                $valueString = trim(implode(' ', $values));

                return empty($valueString) ? "{$directive}" : "{$directive} {$valueString}";
            })
            ->implode(';');
    }

    protected function guardAgainstInvalidDirectives(mixed $directive): void
    {
        if (! $directive instanceof Directive) {
            throw InvalidDirective::notSupported($directive);
        }
    }

    protected function guardAgainstInvalidValues(array $values): void
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

    protected function sanitizeValue(Keyword|string $value): string
    {
        if ($value instanceof Keyword) {
            return "'{$value->value}'";
        }

        if ($this->isHash($value)) {
            return "'{$value}'";
        }

        return $value;
    }

    /** @param class-string<Preset>[] $presets */
    public static function create(
        array $presets = [],
        array $directives = [],
        ?string $reportUri = null,
    ): self {
        $policy = array_reduce($presets, function (Policy $policy, string $className) {
            $preset = app($className);

            if (! is_a($preset, Preset::class, true)) {
                throw InvalidPreset::create($preset);
            }

            $preset->configure($policy);

            return $policy;
        }, new static($reportUri));

        foreach ($directives as [$directive, $contents]) {
            $policy->add($directive, $contents);
        }

        return $policy;
    }
}
