<?php

declare(strict_types=1);

namespace App\Api\Public\Controllers;

use App\Api\Public\Requests\BookAppointmentRequest;
use App\Api\Public\Resources\AppointmentResource;
use Modules\Booking\Actions\BookAppointment;
use Support\Http\Controllers\Controller;

class BookAppointmentController extends Controller
{
    public function store(BookAppointmentRequest $request)
    {
        $appointment = app(BookAppointment::class)->handle(
            barberId: $request->input('barberId'),
            scheduledTo: $request->date('scheduledTo', 'Y-m-d H:i'),
            customerName: $request->input('customerName'),
            customerPhoneNumber: $request->input('customerPhoneNumber'),
        );

        return new AppointmentResource($appointment);
    }
}
