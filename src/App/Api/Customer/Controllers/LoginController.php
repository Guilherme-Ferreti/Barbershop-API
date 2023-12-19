<?php

declare(strict_types=1);

namespace App\Api\Customer\Controllers;

use App\Api\Customer\Requests\LoginRequest;
use App\Api\Customers\Resources\CustomerResource;
use App\Http\Controllers\Controller;
use Domain\Customers\Actions\Login;
use Domain\Customers\Data\Actions\LoginData;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request)
    {
        [$customer, $token] = app(Login::class)->handle(LoginData::fromRequest($request));

        return response()->json([
            'customer'    => new CustomerResource($customer),
            'accessToken' => $token,
        ]);
    }
}
