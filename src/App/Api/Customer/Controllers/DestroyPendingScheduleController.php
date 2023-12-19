<?php

declare(strict_types=1);

namespace App\Api\Customer\Controllers;

use App\Http\Controllers\Controller;

class DestroyPendingScheduleController extends Controller
{
    public function __invoke(Schedule $schedule)
    {
        $schedule->delete();

        return response()->noContent();
    }
}
