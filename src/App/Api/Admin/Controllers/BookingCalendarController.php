<?php

declare(strict_types=1);

namespace App\Api\Admin\Controllers;

use App\Api\Admin\Resources\BookingCalendarResource;
use Modules\Booking\Actions\GetBookingCalendar;
use Support\Http\Controllers\Controller;

class BookingCalendarController extends Controller
{
    public function __invoke()
    {
        return new BookingCalendarResource(app(GetBookingCalendar::class)->handle());
    }
}
