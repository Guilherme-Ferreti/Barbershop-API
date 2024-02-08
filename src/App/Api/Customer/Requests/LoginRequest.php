<?php

declare(strict_types=1);

namespace App\Api\Customer\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'phoneNumber' => ['required', 'string', 'phone:BR'],
        ];
    }
}
