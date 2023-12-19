<?php

declare(strict_types=1);

use App\Api\Public\Controllers\BookingCalendarController;
use App\Api\Public\Controllers\CustomerController;
use App\Api\Public\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;

Route::name('public.')
    ->group(function () {
        Route::post('/schedules', [ScheduleController::class, 'store'])->name('schedules.store');
        Route::get('/schedules/booking-calendar', BookingCalendarController::class)->name('schedules.booking-calendar.show');

        Route::get('/customers/{customer:phone_number}', [CustomerController::class, 'show'])->name('customers.show');
    });
