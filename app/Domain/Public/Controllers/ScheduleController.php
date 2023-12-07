<?php

declare(strict_types=1);

namespace App\Domain\Public\Controllers;

use App\Domain\Common\Resources\ScheduleResource;
use App\Domain\Public\Actions\StoreSchedule;
use App\Domain\Public\Data\Actions\StoreScheduleData;
use App\Domain\Public\Requests\StoreScheduleRequest;
use App\Http\Controllers\Controller;

class ScheduleController extends Controller
{
    public function store(StoreScheduleRequest $request)
    {
        $schedule = app(StoreSchedule::class)->handle(StoreScheduleData::fromRequest($request));

        return new ScheduleResource($schedule);
    }
}
