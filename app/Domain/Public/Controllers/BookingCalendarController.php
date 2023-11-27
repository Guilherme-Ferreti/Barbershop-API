<?php

declare(strict_types=1);

namespace App\Domain\Public\Controllers;

use App\Domain\Public\Actions\GetBookingCalendar;
use App\Http\Controllers\Controller;

class BookingCalendarController extends Controller
{
    public function __invoke()
    {
        return app(GetBookingCalendar::class)->handle()->except('booking_days.holiday.date');
    }
}
