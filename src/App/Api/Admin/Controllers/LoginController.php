<?php

declare(strict_types=1);

namespace App\Api\Admin\Controllers;

use App\Api\Admin\Requests\LoginRequest;
use App\Api\Admin\Resources\UserResource;
use App\Http\Controllers\Controller;
use Domain\Users\Actions\Login;
use Domain\Users\Data\Actions\LoginData;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request)
    {
        [$user, $token] = app(Login::class)->handle(LoginData::fromRequest($request));

        return response()->json([
            'user'        => new UserResource($user),
            'accessToken' => $token,
        ]);
    }
}
