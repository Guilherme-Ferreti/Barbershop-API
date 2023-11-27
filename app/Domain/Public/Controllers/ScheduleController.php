<?php

declare(strict_types=1);

namespace App\Domain\Public\Controllers;

use App\Domain\Public\Actions\StoreSchedule;
use App\Domain\Public\Data\Actions\StoreScheduleData;
use App\Domain\Public\Resources\ScheduleResource;
use App\Http\Controllers\Controller;

class ScheduleController extends Controller
{
    public function store(StoreScheduleData $data)
    {
        $schedule = app(StoreSchedule::class)->handle($data);

        return new ScheduleResource($schedule);
    }
}
