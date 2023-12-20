<?php

declare(strict_types=1);

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Domain\Barbers\Models\Barber;
use Domain\Customers\Models\Customer;

if (! function_exists('current_user')) {
    function current_user(): Barber|Customer|null
    {
        return auth()->user();
    }
}

if (! function_exists('format_date')) {
    function format_date(Carbon|CarbonImmutable $date, string $format = 'Y-m-d H:i:s'): string
    {
        return $date->format($format);
    }
}
