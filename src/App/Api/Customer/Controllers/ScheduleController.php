<?php

declare(strict_types=1);

namespace App\Api\Customer\Controllers;

use App\Api\Customers\Resources\ScheduleResource;
use App\Http\Controllers\Controller;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = current_user()->schedules()->orderByDesc('created_at')->get();

        return ScheduleResource::collection($schedules);
    }
}
