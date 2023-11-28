<?php

declare(strict_types=1);

namespace App\Domain\Authenticated\Actions;

use App\Domain\Authenticated\Data\LoginData;
use App\Domain\Common\Enums\AuthType;
use App\Domain\Common\Models\Customer;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class Login
{
    private LoginData $data;

    private ?Customer $customer;

    public function handle(LoginData $data): array
    {
        $this->data = $data;

        $this->loadCustomer();

        $this->ensureIsNotRateLimited();

        return $this->authenticate();
    }

    private function loadCustomer(): void
    {
        $this->customer = Customer::firstWhere('phone_number', $this->data->phone_number);
    }

    private function ensureIsNotRateLimited(): void
    {
        $throttleKey = $this->throttleKey();

        if (! RateLimiter::tooManyAttempts($throttleKey, config('auth.rate_limit.decay_seconds', 60))) {
            return;
        }

        $seconds = RateLimiter::availableIn($throttleKey);

        throw ValidationException::withMessages([
            'phoneNumber' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    private function throttleKey(): string
    {
        return Str::transliterate(request()->ip());
    }

    private function authenticate(): array
    {
        $throttleKey = $this->throttleKey();

        if (! $this->customer) {
            RateLimiter::hit($throttleKey, config('auth.rate_limit.decay_seconds', 60));

            throw ValidationException::withMessages([
                'phoneNumber' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($throttleKey);

        return [
            $this->customer,
            static::createJwt(),
        ];
    }

    private function createJwt(): string
    {
        $payload = [
            'user' => [
                'id' => $this->customer->id,
            ],
            'type' => AuthType::CUSTOMER->value,
            'iat'  => time(),
            'exp'  => time() + config('jwt.expires_in'),
        ];

        return JWT::encode($payload, config('jwt.secret_key'), 'HS256');
    }
}
