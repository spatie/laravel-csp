<?php

namespace Spatie\LaravelCsp\Profile;

class Csp
{
    use Allows;

    public $profile = [];

    private $keys = [];

    public function __construct()
    {
        $this->keys = collect();

        $this->profile = collect();

        $this->allowsBasics();
    }

    /**
     * @param string $directive
     * @param string|array $value
     * @return \Spatie\LaravelCsp\Profile\Csp
     */
    public function addHeader(string $directive, $value): self
    {
        $value = collect($value)->implode(' ');

        if ($this->keys->contains($directive)) {
            $this->profile[$directive]->push($value);
        }

        if (! $this->keys->contains($directive)) {
            $this->keys->push($directive);

            $this->profile->put($directive, collect($value));
        }
        return $this;
    }
}
