<?php

declare(strict_types=1);

namespace Modules\Auth\Data\Actions;

use Illuminate\Http\Request;

readonly class AuthenticateBarberData
{
    public function __construct(
        public string $password,
    ) {
    }

    public static function fromRequest(Request $request): static
    {
        return new static(...[
            'password' => $request->input('password'),
        ]);
    }
}
