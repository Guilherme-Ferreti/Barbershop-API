<?php

declare(strict_types=1);

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Domain\Common\Enums\AuthType;
use App\Domain\Common\Models\Customer;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

                $model = $authType === AuthType::ADMIN
                    ? User::class
                    : Customer::class;

                return $model::find($jwt->user->id);
            } catch (\Exception) {
                return null;
            }
        });
    }
}
