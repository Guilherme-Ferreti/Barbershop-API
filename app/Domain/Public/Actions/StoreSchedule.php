<?php

declare(strict_types=1);

namespace App\Domain\Public\Actions;

use App\Domain\Common\Models\Customer;
use App\Domain\Common\Models\Schedule;
use App\Domain\Public\Data\Actions\StoreScheduleData;

class StoreSchedule
{
    public function handle(StoreScheduleData $data): Schedule
    {
        $customer = Customer::firstOrCreate(
            ['phone_number' => $data->customer_phone_number],
            ['name' => $data->customer_name],
        );

        $customer->pendingSchedule()->delete();

        return $customer->schedules()->create([
            'scheduled_to'  => $data->scheduled_to,
            'customer_name' => $data->customer_name,
        ]);
    }
}
