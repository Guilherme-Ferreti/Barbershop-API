<?php

declare(strict_types=1);

namespace Modules\Booking\Actions;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Auth\Models\Customer;
use Modules\Booking\Models\Appointment;

class BookAppointment
{
    public function handle(
        Carbon $scheduledTo,
        string $customerName,
        ?string $customerPhoneNumber,
    ): Appointment {
        DB::beginTransaction();

        $customer = null;

        if ($customerPhoneNumber) {
            $customer = Customer::firstOrCreate(
                ['phone_number' => $customerPhoneNumber],
                ['name' => $customerName],
            );

            $customer->pendingAppointment()->delete();
        }

        $appointment = Appointment::create([
            'scheduled_to'  => $scheduledTo,
            'customer_name' => $customerName,
            'customer_id'   => $customer?->id,
        ]);

        DB::commit();

        return $appointment;
    }
}
