<?php

declare(strict_types=1);

namespace App\Api\Public\Requests;

use Domain\Customers\Rules\BrazilianPhoneNumber;
use Domain\Schedules\Rules\AvailableBookingTime;
use Illuminate\Foundation\Http\FormRequest;

class StoreScheduleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'customerPhoneNumber' => ['required', 'string', new BrazilianPhoneNumber],
            'customerName'        => ['required', 'string', 'max:255'],
            'scheduledTo'         => ['bail', 'required', 'date_format:Y-m-d H:i', new AvailableBookingTime],
        ];
    }
}
