<?php

namespace Spatie\Csp\Tests\Doubles\Policies;

use Spatie\Csp\Policies\Basic;

class BasicWithReportOnly extends Basic
{
    public function configure(): void
    {
        $this->reportOnly();
    }
}
