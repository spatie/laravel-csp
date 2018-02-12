<?php

namespace Spatie\LaravelCsp;

use Illuminate\Support\Collection;

class CspPolicyFactory
{
    public function create(Collection $setup): string
    {
        $policy = $this->policyFromSetup($setup);

        $policy->transform(function (Collection $value, string $key) {
            $value = $value->implode(' ');

            return "{$key}: {$value};";
        });

        return $policy->implode(' ');
    }

    public function policyFromSetup(Collection $setup): Collection
    {
        $alreadyUsed = collect();

        $policy = collect();

        $setup->each(function (Collection $setupPart) use ($alreadyUsed, $policy) {
            $setupPart->each(function ($value, string $key) use ($alreadyUsed, $policy) {
                $stringValue = collect($value)->implode(' ');

                if ($alreadyUsed->contains($key)) {
                    $policy[$key]->push($stringValue);
                }

                if (! $alreadyUsed->contains($key)) {
                    $alreadyUsed->push($key);

                    $policy->put($key, collect($stringValue));
                }
            });
        });

        return $policy;
    }
}
