<?php

declare(strict_types=1);

namespace App\Api\Public\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Booking\Rules\AvailableBookingHour;

class BookAppointmentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'customerPhoneNumber' => ['required', 'string', 'phone:BR'],
            'customerName'        => ['required', 'string', 'max:255'],
            'scheduledTo'         => ['bail', 'required', 'date_format:Y-m-d H:i', new AvailableBookingHour],
        ];
    }
}
