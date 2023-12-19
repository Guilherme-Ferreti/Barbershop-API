<?php

declare(strict_types=1);

namespace Domain\Users\Actions;

use Domain\Users\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;
use Support\Actions\AuthenticableLogin;
use Support\Enums\AuthType;

class Login extends AuthenticableLogin
{
    protected function authenticate($data): ?Authenticatable
    {
        return User::query()
            ->get()
            ->filter(fn (User $user) => Hash::check($data->password, $user->password))
            ?->first();
    }

    protected function authFailedMessageKey(): string
    {
        return 'password';
    }

    protected function authType(): AuthType
    {
        return AuthType::ADMIN;
    }
}
