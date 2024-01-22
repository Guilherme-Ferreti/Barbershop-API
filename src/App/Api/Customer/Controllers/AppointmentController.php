<?php

declare(strict_types=1);

namespace App\Api\Customer\Controllers;

use App\Api\Customer\Resources\AppointmentResource;
use Support\Http\Controllers\Controller;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = current_user()->appointments()->orderByDesc('created_at')->get();

        return AppointmentResource::collection($appointments);
    }
}
