<?php

declare(strict_types=1);

namespace App\Domain\Admin\Requests;

use App\Domain\Common\Rules\AvailableBookingTime;
use App\Domain\Common\Rules\BrazilianPhoneNumber;
use Illuminate\Foundation\Http\FormRequest;

class StoreScheduleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'customerPhoneNumber' => ['sometimes', 'nullable', 'string', new BrazilianPhoneNumber],
            'customerName'        => ['required', 'string', 'max:255'],
            'scheduledTo'         => ['bail', 'required', 'date_format:Y-m-d H:i', new AvailableBookingTime],
        ];
    }
}
