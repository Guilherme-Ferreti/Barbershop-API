<?php

declare(strict_types=1);

namespace App\Api\Public\Requests;

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
            'barberId'            => ['required', Rule::exists(Barber::class, 'id')],
            'customerPhoneNumber' => ['required', 'string', 'phone:BR'],
            'customerName'        => ['required', 'string', 'max:255'],
            'scheduledTo'         => ['bail', 'required', 'date_format:Y-m-d H:i', new AvailableBookingHour],
        ];
    }
}
