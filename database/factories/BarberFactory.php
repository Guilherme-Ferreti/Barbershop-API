<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\Models\Barber;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Domain\Barbers\Models\Barber>
 */
class BarberFactory extends Factory
{
    protected $model = Barber::class;

    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name'     => fake()->name(),
            'password' => static::$password ??= Hash::make('password'),
            'is_admin' => false,
        ];
    }

    public function admin(): static
    {
        return $this->state(fn () => [
            'is_admin' => true,
        ]);
    }
}
