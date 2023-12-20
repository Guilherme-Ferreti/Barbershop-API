<?php

declare(strict_types=1);

namespace App\Api\Admin\Controllers;

use App\Api\Admin\Resources\BarberResource;
use Support\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function __invoke()
    {
        return new BarberResource(current_user());
    }
}
