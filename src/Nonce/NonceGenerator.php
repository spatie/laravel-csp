<?php

namespace Spatie\Csp\Nonce;

interface NonceGenerator
{
    public function generate(): string;
}
