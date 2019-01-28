<?php

if (! function_exists('csp_nonce')) {
    function csp_nonce(): string
    {
        return app('csp-nonce');
    }
}


if (! function_exists('csp_attr')) {
    function csp_attr(): \Illuminate\Support\HtmlString
    {
            $nonce = csp_nonce();
            return new \Illuminate\Support\HtmlString(sprintf('nonce="%s"', e($nonce)));

    }
}
