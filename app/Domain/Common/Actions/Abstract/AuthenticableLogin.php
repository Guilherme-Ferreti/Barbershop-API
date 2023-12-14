<?php

declare(strict_types=1);

namespace App\Domain\Common\Actions\Abstract;

use App\Domain\Admin\Data\Actions\LoginData as AdminLoginData;
use App\Domain\Authenticated\Data\Actions\LoginData as CustomerLoginData;
use App\Domain\Common\Enums\AuthType;
use Firebase\JWT\JWT;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

abstract class AuthenticableLogin // AuthenticateAuthenticable, AuthenticateCustomer
{
    private ?Authenticatable $authenticable;

    abstract protected function authenticate(CustomerLoginData|AdminLoginData $data): ?Authenticatable;

    abstract protected function authFailedMessageKey(): string;

    abstract protected function authType(): AuthType;

    public function handle(CustomerLoginData|AdminLoginData $data): array
    {
        $this->ensureIsNotRateLimited();

        if (! $this->authenticable = $this->authenticate($data)) {
            $this->throwValidationFailedException();
        }

        $this->clearRateLimiter();

        return [
            $this->authenticable,
            $this->createJwt(),
        ];
    }

    private function ensureIsNotRateLimited(): void
    {
        $throttleKey = $this->throttleKey();

        if (! RateLimiter::tooManyAttempts($throttleKey, config('auth.rate_limit.decay_seconds', 60))) {
            return;
        }

        $seconds = RateLimiter::availableIn($throttleKey);

        throw ValidationException::withMessages([
            $this->authFailedMessageKey() => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    private function throttleKey(): string
    {
        return Str::transliterate(request()->ip());
    }

    private function throwValidationFailedException(): never
    {
        RateLimiter::hit($this->throttleKey(), config('auth.rate_limit.decay_seconds', 60));

        throw ValidationException::withMessages([
            $this->authFailedMessageKey() => __('auth.failed'),
        ]);
    }

    private function clearRateLimiter(): void
    {
        RateLimiter::clear($this->throttleKey());
    }

    private function createJwt(): string
    {
        $payload = [
            'user' => [
                'id' => $this->authenticable->id,
            ],
            'type' => $this->authType()->value,
            'iat'  => time(),
            'exp'  => time() + config('jwt.expires_in'),
        ];

        return JWT::encode($payload, config('jwt.secret_key'), 'HS256');
    }
}
