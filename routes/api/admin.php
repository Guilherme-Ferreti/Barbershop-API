<?php

declare(strict_types=1);

use App\Domain\Admin\Controllers\LoginController;
use App\Domain\Admin\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::name('admin.')
    ->prefix('/admin')
    ->middleware('admin')
    ->group(function () {
        Route::post('/login', LoginController::class)->name('login')->withoutMiddleware('admin');

        Route::get('/me', ProfileController::class)->name('profile.show');
    });
