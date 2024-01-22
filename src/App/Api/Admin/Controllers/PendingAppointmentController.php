<?php

declare(strict_types=1);

namespace App\Api\Admin\Controllers;

use Modules\Booking\Models\Appointment;
use Support\Http\Controllers\Controller;

class PendingAppointmentController extends Controller
{
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return response()->noContent();
    }
}
