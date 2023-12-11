<?php

declare(strict_types=1);

namespace App\Domain\Authenticated\Controllers;

use App\Domain\Authenticated\Actions\Login;
use App\Domain\Authenticated\Data\Actions\LoginData;
use App\Domain\Authenticated\Requests\LoginRequest;
use App\Domain\Common\Resources\CustomerResource;
use App\Http\Controllers\Controller;

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
