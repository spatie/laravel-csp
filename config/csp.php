<?php

return [

    /*
     * A CSP profile will determine which CSP headers will be set. A valid CSP profile is
     * any class that extends `Spatie\Csp\Profiles\Profile`
     */
    'profile' => Spatie\Csp\Profiles\Basic::class,

    /*
     * This profile which will be put in report only mode. This is great for testing out
     * a new profile or changes to existing csp policy without breaking anyting.
     */
    'report_only_profile' => '',

    /*
     * All violations against the CSP policy will be reported to this url.
     * A great service you could use for this is https://report-uri.com/
     *
     * You can override this setting by calling `reportTo` on your profile.
     */
    'report_uri' => env('CSP_REPORT_URI', ''),

    /*
     * Headers will only be added if this setting is set to true.
     */
    'enabled' => env('CSP_ENABLED', true),

    /*
     * The class responsible for generating the nonces used in inline tags and headers.
     */
    'nonce_generator' => Spatie\Csp\Nonce\RandomString::class,
];
