<?php

declare(strict_types=1);

namespace App\Api\Public\Controllers;

use App\Api\Public\Requests\StoreScheduleRequest;
use App\Api\Public\Resources\StoreScheduleResource;
use App\Http\Controllers\Controller;
use Domain\Schedules\Actions\StoreSchedule;
use Domain\Schedules\Data\Actions\StoreScheduleData;

class StoreScheduleController extends Controller
{
    public function store(StoreScheduleRequest $request)
    {
        $schedule = app(StoreSchedule::class)->handle(StoreScheduleData::fromRequest($request));

        return new StoreScheduleResource($schedule);
    }
}
