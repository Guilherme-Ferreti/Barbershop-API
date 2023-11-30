<?php

declare(strict_types=1);

namespace App\Domain\Authenticated\Controllers;

use App\Domain\Authenticated\Resources\ScheduleIndexResource;
use App\Http\Controllers\Controller;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = currentUser()->schedules()->orderByDesc('created_at')->get();

        return ScheduleIndexResource::collection($schedules);
    }
}
