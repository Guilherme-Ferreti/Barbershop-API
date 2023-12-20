<?php

declare(strict_types=1);

use App\Api\Admin\Controllers\BookingCalendarController;
use App\Api\Admin\Controllers\LoginController;
use App\Api\Admin\Controllers\PendingScheduleController;
use App\Api\Admin\Controllers\ProfileController;
use App\Api\Admin\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;

Route::name('admin.')
    ->prefix('/admin')
    ->middleware('admin')
    ->group(function () {
        Route::post('/login', LoginController::class)->name('login')->withoutMiddleware('admin');

        Route::get('/me', ProfileController::class)->name('profile.show');

        Route::get('/booking-calendar', BookingCalendarController::class)->name('schedules.booking-calendar');

        Route::delete('/pending-schedules/{pendingSchedule}', [PendingScheduleController::class, 'destroy'])->name('pending-schedules.destroy');

        Route::post('/schedules', [ScheduleController::class, 'store'])->name('schedules.store');
    });
