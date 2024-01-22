<?php

declare(strict_types=1);

namespace Modules\Auth\Enums;

use Modules\Auth\Models\Barber;
use Modules\Auth\Models\Customer;

enum AuthType: string
{
    case CUSTOMER = 'customer';

    case BARBER = 'barber';

    public function modelClass(): string
    {
        return match ($this->value) {
            self::CUSTOMER->value => Customer::class,
            self::BARBER->value   => Barber::class,
        };
    }
}
