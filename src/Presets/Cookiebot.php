<?php

namespace Spatie\Csp\Presets;

use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;
use Spatie\Csp\Policy;
use Spatie\Csp\Preset;

class Cookiebot implements Preset
{
    // Allows the Cookiebot consent banner (https://www.cookiebot.com) on both its default and its European CDN.
    // uc.js is served from consent.cookiebot.*, loads the banner from consentcdn.cookiebot.*
    // and injects a <style> block for the banner.
    public function configure(Policy $policy): void
    {
        $policy
            ->add(Directive::SCRIPT, [
                'https://consent.cookiebot.com',
                'https://consentcdn.cookiebot.com',
                'https://consent.cookiebot.eu',
                'https://consentcdn.cookiebot.eu',
            ])
            ->add(Directive::CONNECT, [
                'https://consent.cookiebot.com',
                'https://consentcdn.cookiebot.com',
                'https://consent.cookiebot.eu',
                'https://consentcdn.cookiebot.eu',
            ])
            ->add(Directive::FRAME, [
                'https://consentcdn.cookiebot.com',
                'https://consentcdn.cookiebot.eu',
            ])
            ->add(Directive::IMG, 'https://imgsct.cookiebot.com')
            ->add(Directive::STYLE, Keyword::UNSAFE_INLINE);
    }
}
