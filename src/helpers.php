<?php

function nonce()
{
    $csp = new \Spatie\LaravelCsp\Csp();

    return $csp->profile;
}
