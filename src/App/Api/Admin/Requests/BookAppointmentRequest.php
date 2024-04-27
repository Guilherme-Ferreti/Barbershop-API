<?php

declare(strict_types=1);

namespace App\Api\Admin\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Auth\Models\Barber;
use Modules\Booking\Rules\AvailableBookingHour;

class BookAppointmentRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function rules(): array
    {
        return [
            'barberId'            => ['required', 'string', Rule::exists(Barber::class, 'id')],
            'scheduledTo'         => ['bail', 'required', 'date_format:Y-m-d H:i', new AvailableBookingHour],
            'customerName'        => ['required', 'string', 'max:255'],
            'customerPhoneNumber' => ['sometimes', 'nullable', 'string', 'phone:BR'],
        ];
    }
}
