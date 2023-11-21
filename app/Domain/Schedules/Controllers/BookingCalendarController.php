<?php

declare(strict_types=1);

namespace App\Domain\Schedules\Controllers;

use App\Domain\Schedules\Actions\GetBookingCalendar;
use App\Http\Controllers\Controller;

class BookingCalendarController extends Controller
{
    public function __invoke()
    {
        return app(GetBookingCalendar::class)->handle()->except('booking_days.holiday.date');
    }
}
