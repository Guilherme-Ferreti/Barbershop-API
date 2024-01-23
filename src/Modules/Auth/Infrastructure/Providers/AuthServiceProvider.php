<?php

declare(strict_types=1);

namespace Modules\Auth\Infrastructure\Providers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Auth\Enums\AuthType;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Auth::viaRequest('jwt', function (Request $request) {
            try {
                $jwt = JWT::decode((string) $request->bearerToken(), new Key(config('jwt.secret_key'), 'HS256'));

                if ($jwt->exp <= time()) {
                    return null;
                }

                $authType = AuthType::from($jwt->type);

                return $authType->modelClass()::find($jwt->user->id);
            } catch (\Exception) {
                return null;
            }
        });
    }
}
