<?php

declare(strict_types=1);

namespace Domain\Barbers\Actions;

use Domain\Barbers\Models\Barber;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;
use Support\Actions\AuthenticableLogin;
use Support\Enums\AuthType;

class Login extends AuthenticableLogin
{
    protected function authenticate($data): ?Authenticatable
    {
        return Barber::query()
            ->get()
            ->filter(fn (Barber $barber) => Hash::check($data->password, $barber->password))
            ?->first();
    }

    protected function authFailedMessageKey(): string
    {
        return 'password';
    }

    protected function authType(): AuthType
    {
        return AuthType::BARBER;
    }
}
