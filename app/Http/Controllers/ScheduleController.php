<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\StoreSchedule;
use App\Data\Actions\StoreScheduleData;
use App\Resources\ScheduleResource;

class ScheduleController extends Controller
{
    public function store(StoreScheduleData $data)
    {
        $schedule = app(StoreSchedule::class)->handle($data);

        return new ScheduleResource($schedule);
    }
}
