<?php

declare(strict_types=1);

use App\Domain\Authenticated\Controllers\LoginController;
use App\Domain\Authenticated\Controllers\ProfileController;
use App\Domain\Authenticated\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;

Route::name('authenticated.')
    ->prefix('/auth')
    ->middleware('auth')
    ->group(function () {
        Route::post('/login', LoginController::class)->name('login')->withoutMiddleware('auth');

        Route::get('/me', [ProfileController::class, 'show'])->name('profile.show');
        Route::patch('/me', [ProfileController::class, 'update'])->name('profile.update');

        Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules.index');
        Route::delete('/pending-schedules/{pendingSchedule}', [ScheduleController::class, 'destroy'])->name('pending-schedules.destroy')->can('destroy', 'pendingSchedule');
    });
