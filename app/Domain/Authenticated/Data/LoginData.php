<?php

declare(strict_types=1);

namespace App\Domain\Authenticated\Data;

use App\Domain\Common\Rules\BrazilianPhoneNumber;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\CamelCaseMapper;

#[MapInputName(CamelCaseMapper::class)]
class LoginData extends Data
{
    public string $phone_number;

    public static function rules(): array
    {
        return [
            'phoneNumber' => ['required', 'string', new BrazilianPhoneNumber],
        ];
    }
}
