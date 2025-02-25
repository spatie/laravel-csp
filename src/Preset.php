<?php

namespace Spatie\Csp;

interface Preset
{
    public function configure(Policy $policy): void;
}
