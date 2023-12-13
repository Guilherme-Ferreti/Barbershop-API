<?php

declare(strict_types=1);

namespace App\Domain\Authenticated\Controllers;

use App\Domain\Common\Models\Schedule;
use App\Http\Controllers\Controller;

class PendingScheduleController extends Controller
{
    public function destroy(Schedule $schedule)
    {
        $schedule->delete();

        return response()->noContent();
    }
}
