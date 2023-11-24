<?php

declare(strict_types=1);

namespace App\Domain\Schedules\Actions;

use App\Domain\Customers\Models\Customer;
use App\Domain\Schedules\Data\Actions\StoreScheduleData;
use App\Domain\Schedules\Models\Schedule;

class StoreSchedule
{
    public function handle(StoreScheduleData $data): Schedule
    {
        $customer = Customer::firstOrCreate(
            ['phone_number' => $data->customer_phone_number],
            ['name' => $data->customer_name],
        );

        return $customer->schedules()->create([
            'scheduled_to'  => $data->scheduled_to,
            'customer_name' => $data->customer_name,
        ]);
    }
}
