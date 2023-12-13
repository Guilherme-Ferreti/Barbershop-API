<?php

declare(strict_types=1);

namespace App\Domain\Admin\Controllers;

use App\Domain\Admin\Actions\StoreSchedule;
use App\Domain\Admin\Data\Actions\StoreScheduleData;
use App\Domain\Admin\Requests\StoreScheduleRequest;
use App\Domain\Common\Resources\ScheduleResource;
use App\Http\Controllers\Controller;

class ScheduleController extends Controller
{
    public function store(StoreScheduleRequest $request)
    {
        $schedule = app(StoreSchedule::class)->handle(StoreScheduleData::fromRequest($request));

        return new ScheduleResource($schedule);
    }
}
