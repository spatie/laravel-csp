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

    public function add(string|array $directives, string|array|bool $values): self
    {
        foreach (Arr::wrap($directives) as $directive) {
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
        }

        return $this;
    }

    public function addNonce(string $directive): self
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

    public function isEmpty(): bool
    {
        return empty($this->directives);
    }

    public function getContents(): string
    {
        $directives = $this->directives;

        if ($this->reportUri) {
            $directives[Directive::REPORT] = [$this->reportUri];
        }

        return collect($directives)
            ->map(function (array $values, string $directive) {
                $valueString = trim(implode(' ', $values));

                return empty($valueString) ? "{$directive}" : "{$directive} {$valueString}";
            })
            ->implode(';');
    }

    protected function guardAgainstInvalidDirectives(string $directive): void
    {
        if (! Directive::isValid($directive)) {
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

    /** @param class-string<Preset>[] $presets */
    public static function create(
        array $presets,
        ?string $reportUri = null,
    ): self {
        return array_reduce($presets, function (Policy $policy, string $className) {
            $preset = app($className);

            if (! is_a($preset, Preset::class, true)) {
                throw InvalidPreset::create($preset);
            }

            $preset->configure($policy);

            return $policy;
        }, new static($reportUri));
    }
}
