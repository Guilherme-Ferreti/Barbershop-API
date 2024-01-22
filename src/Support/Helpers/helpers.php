<?php

declare(strict_types=1);

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Modules\Auth\Models\Barber;
use Modules\Auth\Models\Customer;

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
