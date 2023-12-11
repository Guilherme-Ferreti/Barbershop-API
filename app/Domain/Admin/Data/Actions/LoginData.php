<?php

declare(strict_types=1);

namespace App\Domain\Admin\Data\Actions;

use Illuminate\Http\Request;

class LoginData
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
