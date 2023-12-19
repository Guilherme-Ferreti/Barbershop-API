<?php

declare(strict_types=1);

namespace Domain\Users\Data\Actions;

use Illuminate\Http\Request;

readonly class LoginData
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
