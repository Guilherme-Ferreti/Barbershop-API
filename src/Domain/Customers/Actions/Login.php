<?php

declare(strict_types=1);

namespace Domain\Customers\Actions;

use Domain\Customers\Models\Customer;
use Illuminate\Contracts\Auth\Authenticatable;
use Support\Actions\AuthenticableLogin;
use Support\Enums\AuthType;

class Login extends AuthenticableLogin
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
