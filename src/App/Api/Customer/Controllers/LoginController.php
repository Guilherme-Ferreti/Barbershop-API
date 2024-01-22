<?php

declare(strict_types=1);

namespace App\Api\Customer\Controllers;

use App\Api\Customer\Requests\LoginRequest;
use App\Api\Customer\Resources\CustomerResource;
use Modules\Auth\Actions\AuthenticateCustomer;
use Modules\Auth\Data\Actions\AuthenticateCustomerData;
use Support\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request)
    {
        [$customer, $token] = app(AuthenticateCustomer::class)->handle(AuthenticateCustomerData::fromRequest($request));

        return response()->json([
            'customer'    => new CustomerResource($customer),
            'accessToken' => $token,
        ]);
    }
}
