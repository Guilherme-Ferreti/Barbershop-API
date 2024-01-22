<?php

declare(strict_types=1);

namespace Modules\Auth\Actions;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\Enums\AuthType;
use Modules\Auth\Models\Barber;

class AuthenticateBarber extends AuthenticateAuthenticable
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
