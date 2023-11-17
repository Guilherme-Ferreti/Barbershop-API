<?php

declare(strict_types=1);

namespace App\Domain\Schedules\Controllers;

use App\Domain\Schedules\Actions\StoreSchedule;
use App\Domain\Schedules\Data\Actions\StoreScheduleData;
use App\Domain\Schedules\Resources\ScheduleResource;
use App\Http\Controllers\Controller;

class ScheduleController extends Controller
{
    public function store(StoreScheduleData $data)
    {
        $schedule = app(StoreSchedule::class)->handle($data);

        return new ScheduleResource($schedule);
    }
}
