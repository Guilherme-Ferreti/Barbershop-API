<?php

declare(strict_types=1);

namespace App\Domain\Authenticated\Actions;

use App\Domain\Admin\Data\Actions\LoginData as AdminLoginData;
use App\Domain\Authenticated\Data\Actions\LoginData as CustomerLoginData;
use App\Domain\Common\Actions\Abstract\BaseLogin;
use App\Domain\Common\Enums\AuthType;
use App\Domain\Common\Models\Customer;
use Illuminate\Contracts\Auth\Authenticatable;

class Login extends BaseLogin
{
    protected function authenticate(CustomerLoginData|AdminLoginData $data): ?Authenticatable
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
