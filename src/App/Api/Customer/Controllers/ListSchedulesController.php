<?php

declare(strict_types=1);

namespace App\Api\Customer\Controllers;

use App\Api\Customers\Resources\ScheduleResource;
use App\Http\Controllers\Controller;

class ListSchedulesController extends Controller
{
    public function __invoke()
    {
        $schedules = current_user()->schedules()->orderByDesc('created_at')->get();

        return ScheduleResource::collection($schedules);
    }
}
