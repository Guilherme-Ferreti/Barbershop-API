<?php

declare(strict_types=1);

namespace App\Api\Customer\Controllers;

use App\Api\Customer\Requests\UpdateProfileRequest;
use App\Api\Customer\Resources\CustomerResource;
use Support\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function show()
    {
        return new CustomerResource(current_user());
    }

    public function update(UpdateProfileRequest $request)
    {
        current_user()->update($request->validated());

        return new CustomerResource(current_user());
    }
}
