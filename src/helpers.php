<?php

if (! function_exists('csp_nonce')) {
    function csp_nonce(): string
    {
        return app('csp-nonce');
    }
}
