<?php

return [

    /*
     * A csp profile will determine which csp headers will be set. A valid csp profile is
     * any class that extends `Spatie\Csp\Profiles\Profile`
     */
    'profile' => \Spatie\Csp\Profiles\Basic::class,

    /*
     * This profile which will be put in report only mode. This is great for testing out
     * a new profile or changes to existing csp policy without breaking anyting.
     */
    'report_only_profile' => '',

    /*
     * All violations against the csp policy will be reported to this url.
     * A great service you could use for this is https://report-uri.com/
     *
     * You can override this setting by calling `reportTo` on your profile.
     */
    'report_uri' => env('CSP_REPORT_URI', ''),

    /*
     * Headers will only be added if this setting is enabled
     */
    'enabled' => env('CSP_ENABLED', true),
];
