<?php

declare(strict_types=1);

namespace App\Domain\Authenticated\Controllers;

use App\Domain\Authenticated\Actions\Login;
use App\Domain\Authenticated\Data\LoginData;
use App\Domain\Common\Resources\CustomerResource;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function __invoke(LoginData $data)
    {
        [$customer, $token] = app(Login::class)->handle($data);

        return response()->json([
            'customer'    => new CustomerResource($customer),
            'accessToken' => $token,
        ]);
    }
}
