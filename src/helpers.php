<?php

function cspNonce(): string
{
    return app('csp-nonce');
}