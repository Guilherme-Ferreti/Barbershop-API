<?php

declare(strict_types=1);

use App\Domain\Common\Models\Customer;
use App\Models\User;

function currentUser(): User|Customer
{
    return auth()->user();
}
