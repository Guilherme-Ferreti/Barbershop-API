<?php

declare(strict_types=1);

use App\Domain\Common\Models\Customer;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonImmutable;

if (! function_exists('current_user')) {
    function current_user(): User|Customer|null
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
