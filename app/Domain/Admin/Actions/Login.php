<?php

declare(strict_types=1);

namespace App\Domain\Admin\Actions;

use App\Domain\Admin\Data\Actions\LoginData as AdminLoginData;
use App\Domain\Authenticated\Data\Actions\LoginData as CustomerLoginData;
use App\Domain\Common\Actions\Abstract\BaseLogin;
use App\Domain\Common\Enums\AuthType;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;

class Login extends BaseLogin
{
    protected function authenticate(CustomerLoginData|AdminLoginData $data): ?Authenticatable
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
