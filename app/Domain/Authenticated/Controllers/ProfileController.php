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
        return new CustomerResource(currentUser());
    }

    public function update(UpdateProfileRequest $request)
    {
        currentUser()->update($request->validated());

        return new CustomerResource(currentUser());
    }
}
