<?php

declare(strict_types=1);

namespace App\Api\Customer\Controllers;

use Domain\Schedules\Models\Schedule;
use Support\Http\Controllers\Controller;

class PendingScheduleController extends Controller
{
    public function destroy(Schedule $schedule)
    {
        $schedule->delete();

        return response()->noContent();
    }
}
