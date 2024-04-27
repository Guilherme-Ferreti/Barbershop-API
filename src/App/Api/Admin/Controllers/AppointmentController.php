<?php

declare(strict_types=1);

namespace App\Api\Admin\Controllers;

use App\Api\Admin\Requests\BookAppointmentRequest;
use App\Api\Admin\Resources\AppointmentResource;
use Modules\Booking\Actions\BookAppointment;
use Support\Http\Controllers\Controller;

class AppointmentController extends Controller
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
