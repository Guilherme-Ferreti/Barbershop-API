<?php

declare(strict_types=1);

namespace App\Api\Public\Controllers;

use App\Api\Public\Requests\StoreScheduleRequest;
use App\Api\Public\Resources\ScheduleResource;
use Domain\Schedules\Actions\StoreSchedule;
use Domain\Schedules\Data\Actions\StoreScheduleData;
use Support\Http\Controllers\Controller;

class ScheduleController extends Controller
{
    public function store(StoreScheduleRequest $request)
    {
        $schedule = app(StoreSchedule::class)->handle(StoreScheduleData::fromRequest($request));

        return new ScheduleResource($schedule);
    }
}
