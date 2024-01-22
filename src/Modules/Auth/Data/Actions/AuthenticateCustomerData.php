<?php

declare(strict_types=1);

namespace Modules\Auth\Data\Actions;

use Illuminate\Http\Request;

class AuthenticateCustomerData
{
    public function __construct(
        public string $phone_number,
    ) {
    }

    public static function fromRequest(Request $request): static
    {
        return new static(...[
            'phone_number' => $request->input('phoneNumber'),
        ]);
    }
}
