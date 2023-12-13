<?php

declare(strict_types=1);

namespace App\Domain\Admin\Actions;

use App\Domain\Admin\Data\Actions\StoreScheduleData;
use App\Domain\Common\Models\Schedule;
use App\Domain\Public\Actions\StoreSchedule as PublicStoreSchedule;
use App\Domain\Public\Data\Actions\StoreScheduleData as PublicStoreScheduleData;

class StoreSchedule
{
    public function handle(StoreScheduleData $data): Schedule
    {
        if ($data->customer_phone_number) {
            return app(PublicStoreSchedule::class)->handle(new PublicStoreScheduleData(...[
                'customer_phone_number' => $data->customer_phone_number,
                'customer_name'         => $data->customer_name,
                'scheduled_to'          => $data->scheduled_to,
            ]));
        }

        return Schedule::create([
            'scheduled_to'  => $data->scheduled_to,
            'customer_name' => $data->customer_name,
        ]);
    }
}
