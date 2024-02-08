<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Booking\Rules\AvailableBookingHour;

class BookAppointmentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'scheduledTo'         => ['bail', 'required', 'date_format:Y-m-d H:i', new AvailableBookingHour],
            'customerName'        => ['required', 'string', 'max:255'],
            'customerPhoneNumber' => ['sometimes', 'nullable', 'string', 'phone:BR'],
        ];
    }
}
