<?php

declare(strict_types=1);

namespace App\Domain\Public\Controllers;

use App\Domain\Public\Actions\GetBookingCalendar;
use App\Domain\Public\Resources\BookingCalendarResource;
use App\Http\Controllers\Controller;

class BookingCalendarController extends Controller
{
    public function __invoke()
    {
        return new BookingCalendarResource(app(GetBookingCalendar::class)->handle());
    }
}
