<?php

namespace Spatie\Csp\Nonce;

class RandomString implements NonceGenerator
{
    public function generate(): string
    {
        return str_random(128);
    }
}
