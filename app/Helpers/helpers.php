<?php

declare(strict_types=1);

use App\Domain\Common\Models\Customer;
use App\Models\User;
use Illuminate\Support\Carbon;

function currentUser(): User|Customer
{
    return auth()->user();
}

function formatDate(Carbon $date, string $format = 'Y-m-d H:i:s'): string
{
    return $date->format($format);
}
