<?php

declare(strict_types=1);

use App\Domain\Admin\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::name('admin.')
    ->prefix('/admin')
    ->middleware('auth')
    ->group(function () {
        Route::post('/login', LoginController::class)->name('login')->withoutMiddleware('auth');
    });
