<?php

declare(strict_types=1);

namespace App\Api\Public\Controllers;

use App\Api\Public\Resources\BookingCalendarResource;
use Domain\Schedules\Actions\GetBookingCalendar;
use Support\Http\Controllers\Controller;

class BookingCalendarController extends Controller
{
    public function __invoke()
    {
        return new BookingCalendarResource(app(GetBookingCalendar::class)->handle());
    }
}
