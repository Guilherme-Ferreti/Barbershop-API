<?php

declare(strict_types=1);

namespace App\Domain\Admin\Actions;

use App\Domain\Admin\Data\Actions\LoginData;

class Login
{
    public function handle(LoginData $data): array
    {
        dd($data);
    }
}
