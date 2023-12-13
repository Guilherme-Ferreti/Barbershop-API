<?php

declare(strict_types=1);

use App\Domain\Common\Models\Customer;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonImmutable;

function currentUser(): User|Customer|null
{
    return auth()->user();
}

function formatDate(Carbon|CarbonImmutable $date, string $format = 'Y-m-d H:i:s'): string
{
    return $date->format($format);
}
