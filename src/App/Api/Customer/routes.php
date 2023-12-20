<?php

declare(strict_types=1);

use App\Api\Customer\Controllers\LoginController;
use App\Api\Customer\Controllers\PendingScheduleController;
use App\Api\Customer\Controllers\ProfileController;
use App\Api\Customer\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;

Route::name('customer.')
    ->prefix('/auth')
    ->middleware(['auth', 'customer'])
    ->group(function () {
        Route::post('/login', LoginController::class)->name('login')->withoutMiddleware(['auth', 'customer']);

        Route::get('/me', [ProfileController::class, 'show'])->name('profile.show');
        Route::patch('/me', [ProfileController::class, 'update'])->name('profile.update');

        Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules.index');
        Route::delete('/pending-schedules/{pendingSchedule}', [PendingScheduleController::class, 'destroy'])->name('pending-schedules.destroy')->can('destroy', 'pendingSchedule');
    });
