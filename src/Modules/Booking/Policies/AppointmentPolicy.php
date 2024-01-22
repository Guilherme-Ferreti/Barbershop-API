<?php

declare(strict_types=1);

namespace Modules\Booking\Policies;

use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Auth\User as Authenticable;
use Modules\Auth\Models\Barber;
use Modules\Auth\Models\Customer;
use Modules\Booking\Models\Appointment;

class AppointmentPolicy
{
    public function before(Authenticable $user): ?bool
    {
        if ($user instanceof Barber && $user->is_admin) {
            return true;
        }

        return null;
    }

    public function destroy(Customer $customer, Appointment $appointment): Response
    {
        return $appointment->customer_id === $customer->id
            ? Response::allow()
            : Response::denyAsNotFound(__('Appointment not found.'));
    }
}
