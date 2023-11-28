<?php

declare(strict_types=1);

namespace App\Domain\Authenticated\Controllers;

use App\Domain\Authenticated\Data\UpdateProfileData;
use App\Domain\Common\Resources\CustomerResource;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function show()
    {
        return new CustomerResource(currentUser());
    }

    public function update(UpdateProfileData $data)
    {
        currentUser()->update($data->toArray());

        return new CustomerResource(currentUser());
    }
}
