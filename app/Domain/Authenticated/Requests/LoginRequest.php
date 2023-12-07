<?php

declare(strict_types=1);

namespace App\Domain\Authenticated\Requests;

use App\Domain\Common\Rules\BrazilianPhoneNumber;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'phoneNumber' => ['required', 'string', new BrazilianPhoneNumber],
        ];
    }
}
