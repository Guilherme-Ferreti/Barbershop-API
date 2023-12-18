<?php

declare(strict_types=1);

namespace App\Domain\Authenticated\Controllers;

use App\Domain\Authenticated\Requests\UpdateProfileRequest;
use App\Domain\Common\Resources\CustomerResource;
use App\Http\Controllers\Controller;

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
