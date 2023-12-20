<?php

declare(strict_types=1);

namespace App\Api\Admin\Controllers;

use App\Api\Admin\Requests\LoginRequest;
use App\Api\Admin\Resources\BarberResource;
use Domain\Barbers\Actions\Login;
use Domain\Barbers\Data\Actions\LoginData;
use Support\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request)
    {
        [$user, $token] = app(Login::class)->handle(LoginData::fromRequest($request));

        return response()->json([
            'user'        => new BarberResource($user),
            'accessToken' => $token,
        ]);
    }
}
