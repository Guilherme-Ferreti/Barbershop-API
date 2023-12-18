<?php

declare(strict_types=1);

namespace App\Domain\Authenticated\Controllers;

use App\Domain\Common\Resources\ScheduleResource;
use App\Http\Controllers\Controller;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = current_user()->schedules()->orderByDesc('created_at')->get();

        return ScheduleResource::collection($schedules);
    }
}
