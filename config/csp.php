<?php

return [

    /*
     * A csp profile will determine which csp headers will be set.
     */
    'profile' => \Spatie\Csp\Profiles\Strict::class,

    /*
     * Headers will only be added if this setting is enabled
     */
    'enabled' => env('CSP_ENABLED', true),

    /*
     * All violations against the csp policy will be report to this url.
     * A great server you could use for this is https://report-uri.com/
     */
    'report_uri' => env('CSP_REPORT_URI', ''),

    /*
     * To test your policy you can turn on the report only mode.
     * The policy will not be enforced by the browser, but any violations
     * are reported to the given uri
     */
    'report_only' => env('CSP_ONLY_REPORT', false),

];
