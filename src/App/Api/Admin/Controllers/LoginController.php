<?php

declare(strict_types=1);

namespace App\Api\Admin\Controllers;

use App\Api\Admin\Requests\LoginRequest;
use App\Api\Admin\Resources\BarberResource;
use Modules\Auth\Actions\AuthenticateBarber;
use Modules\Auth\Data\Actions\AuthenticateBarberData;
use Support\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request)
    {
        [$user, $token] = app(AuthenticateBarber::class)->handle(AuthenticateBarberData::fromRequest($request));

        return response()->json([
            'user'        => new BarberResource($user),
            'accessToken' => $token,
        ]);
    }
}
