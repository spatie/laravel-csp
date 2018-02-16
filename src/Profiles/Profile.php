<?php

namespace Spatie\Csp\Profiles\Profile;

use Spatie\Csp\Profiles\Directive;
use Spatie\Csp\Exceptions\InvalidDirective;
use Symfony\Component\HttpFoundation\Response;

abstract class Profile
{
    /** @var array */
    protected $directives = [];

    protected $reportOnly = false;

    public function addHeader(string $directive, string $value): self
    {
        $this->guardAgainstInvalidDirectives($directive);

        $this->directives[$directive][] = $value;

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
        $this->directives['report-uri'] = $uri;

        $this->directives['report-to'] = json_encode([
           'url' => $uri,
           'group-name' => class_basename(static::class),
           'max-age => 60 * 60 * 24 * 7 * 30',
        ]);

        return $this;
    }

    public function applyTo(Response $response)
    {
        $headerName = $this->reportOnly
            ? 'Content-Security-Policy-Report-Only'
            : 'Content-Security-Policy';

        $response->headers->set($headerName, (string) $this);
    }

    protected function guardAgainstInvalidDirectives(string $directive)
    {
        if (! Directive::isValid($directive)) {
            throw InvalidDirective::notSupported($directive);
        }
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
}
