<?php

if (!function_exists('cspNonce')) {
    function cspNonce(): string
    {
        return app('csp-nonce');
    }
}
