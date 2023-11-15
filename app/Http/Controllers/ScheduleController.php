<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\StoreSchedule;
use App\Resources\ScheduleResource;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function store(Request $request)
    {
        $schedule = app(StoreSchedule::class)->handle($request->all());

        return new ScheduleResource($schedule);
    }
}
