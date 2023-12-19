<?php

declare(strict_types=1);

namespace App\Api\Admin\Controllers;

use App\Api\Admin\Resources\BookingCalendarResource;
use App\Http\Controllers\Controller;
use Domain\Schedules\Actions\GetBookingCalendar;

class BookingCalendarController extends Controller
{
    public function __invoke()
    {
        return new BookingCalendarResource(app(GetBookingCalendar::class)->handle());
    }
}
