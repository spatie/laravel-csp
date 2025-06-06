<?php

namespace Spatie\Csp\Nonce;

interface NonceGenerator
{
    /**
     * Generates a CSP nonce string.
     *
     * The nonce should be a base64-value derived from at least 16 bytes of
     * cryptographically secure random data, safe for use in HTML attributes and HTTP headers.
     *
     * @see https://www.w3.org/TR/CSP3/#grammardef-base64-value
     */
    public function generate(): string;
}
