<?php

declare(strict_types=1);

namespace App\Api\Admin\Controllers;

use App\Domain\Admin\Resources\UserResource;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function __invoke()
    {
        return new UserResource(current_user());
    }
}
