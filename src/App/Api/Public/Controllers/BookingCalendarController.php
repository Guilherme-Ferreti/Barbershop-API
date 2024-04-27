<?php

declare(strict_types=1);

namespace App\Api\Public\Controllers;

use App\Api\Public\Resources\BookingCalendarResource;
use Modules\Booking\Actions\GetBookingCalendarForAllBarbers;
use Support\Http\Controllers\Controller;

class BookingCalendarController extends Controller
{
    public function __invoke()
    {
        $calendars = app(GetBookingCalendarForAllBarbers::class)->handle();

        return BookingCalendarResource::collection($calendars);
    }
}
