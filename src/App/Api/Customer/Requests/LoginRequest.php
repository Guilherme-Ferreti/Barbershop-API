<?php

declare(strict_types=1);

namespace App\Api\Customer\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Booking\Rules\BrazilianPhoneNumber;

class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'phoneNumber' => ['required', 'string', new BrazilianPhoneNumber],
        ];
    }
}
