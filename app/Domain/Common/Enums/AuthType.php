<?php

declare(strict_types=1);

namespace App\Domain\Common\Enums;

enum AuthType: string
{
    case CUSTOMER = 'customer';

    case ADMIN = 'admin';
}
