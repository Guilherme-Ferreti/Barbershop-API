<?php

declare(strict_types=1);

namespace App\Domain\Admin\Controllers;

use App\Domain\Admin\Actions\Login;
use App\Domain\Admin\Data\Actions\LoginData;
use App\Domain\Admin\Requests\LoginRequest;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request)
    {
        dd(app(Login::class)->handle(LoginData::fromRequest($request)));
    }
}
