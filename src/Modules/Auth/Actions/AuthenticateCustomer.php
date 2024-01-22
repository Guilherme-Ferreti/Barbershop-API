<?php

declare(strict_types=1);

namespace Modules\Auth\Actions;

use Illuminate\Contracts\Auth\Authenticatable;
use Modules\Auth\Enums\AuthType;
use Modules\Auth\Models\Customer;

class AuthenticateCustomer extends AuthenticateAuthenticable
{
    protected function authenticate($data): ?Authenticatable
    {
        return Customer::firstWhere('phone_number', $data->phone_number);
    }

    protected function authFailedMessageKey(): string
    {
        return 'phoneNumber';
    }

    protected function authType(): AuthType
    {
        return AuthType::CUSTOMER;
    }
}
