<?php

declare(strict_types=1);

namespace Support\Enums;

enum AuthType: string
{
    case CUSTOMER = 'customer';

    case ADMIN = 'admin';
}
