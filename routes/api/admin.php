<?php

declare(strict_types=1);

use App\Domain\Admin\Controllers\BookingCalendarController;
use App\Domain\Admin\Controllers\LoginController;
use App\Domain\Admin\Controllers\PendingScheduleController;
use App\Domain\Admin\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::name('admin.')
    ->prefix('/admin')
    ->middleware('admin')
    ->group(function () {
        Route::post('/login', LoginController::class)->name('login')->withoutMiddleware('admin');

        Route::get('/me', ProfileController::class)->name('profile.show');

        Route::get('/booking-calendar', BookingCalendarController::class)->name('schedules.booking-calendar.show');

        Route::delete('/pending-schedules/{pendingSchedule}', [PendingScheduleController::class, 'destroy'])->name('pending-schedules.destroy');
    });
