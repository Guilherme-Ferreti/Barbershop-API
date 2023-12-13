<?php

declare(strict_types=1);

namespace App\Domain\Admin\Controllers;

use App\Domain\Admin\Resources\BookingCalendarResource;
use App\Domain\Common\Actions\GetBookingCalendar;
use App\Http\Controllers\Controller;

class BookingCalendarController extends Controller
{
    public function __invoke()
    {
        return new BookingCalendarResource(app(GetBookingCalendar::class)->handle());
    }
}
