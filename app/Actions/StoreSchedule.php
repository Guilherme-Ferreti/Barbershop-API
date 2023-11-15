<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Customer;
use App\Models\Schedule;

class StoreSchedule
{
    public function handle(array $data): Schedule
    {
        $customer = Customer::firstOrCreate(
            ['phone_number' => $data['customerPhoneNumber']],
            ['name'         => $data['customerName']],
        );

        return $customer->schedules()->create([
            'scheduled_to'  => $data['scheduledTo'],
            'customer_name' => $customer->name,
        ]);
    }
}
