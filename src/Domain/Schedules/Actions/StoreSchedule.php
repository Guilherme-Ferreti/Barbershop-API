<?php

declare(strict_types=1);

namespace Domain\Schedules\Actions;

use Domain\Customers\Models\Customer;
use Domain\Schedules\Data\Actions\StoreScheduleData;
use Domain\Schedules\Models\Schedule;
use Illuminate\Support\Facades\DB;

class StoreSchedule
{
    public function handle(StoreScheduleData $data): Schedule
    {
        DB::beginTransaction();

        if ($data->customer_phone_number) {
            $customer = Customer::firstOrCreate(
                ['phone_number' => $data->customer_phone_number],
                ['name' => $data->customer_name],
            );

            $customer->pendingSchedule()->delete();
        }

        $schedule = Schedule::create([
            'scheduled_to'  => $data->scheduled_to,
            'customer_name' => $data->customer_name,
            'customer_id'   => $customer?->id,
        ]);

        DB::commit();

        return $schedule;
    }
}
