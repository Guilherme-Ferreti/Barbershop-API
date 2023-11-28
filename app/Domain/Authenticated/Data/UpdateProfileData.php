<?php

declare(strict_types=1);

namespace App\Domain\Authenticated\Data;

use Spatie\LaravelData\Data;

class UpdateProfileData extends Data
{
    public string $name;

    public static function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
        ];
    }
}
