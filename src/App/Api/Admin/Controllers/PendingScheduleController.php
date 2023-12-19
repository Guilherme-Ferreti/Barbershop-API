<?php

declare(strict_types=1);

namespace App\Api\Admin\Controllers;

use App\Http\Controllers\Controller;
use Domain\Schedules\Models\Schedule;

class PendingScheduleController extends Controller
{
    public function destroy(Schedule $schedule)
    {
        $schedule->delete();

        return response()->noContent();
    }
}
