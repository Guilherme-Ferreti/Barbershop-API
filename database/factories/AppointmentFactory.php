<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Auth\Models\Customer;
use Modules\Booking\Models\Appointment;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Modules\Booking\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition(): array
    {
        return [
            'customer_id'   => Customer::factory(),
            'customer_name' => fn (array $attributes) => Customer::find($attributes['customer_id'])->name,
            'scheduled_to'  => fake()->dateTime(),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'scheduled_to' => fake()->dateTimeBetween(startDate: '+1 hour', endDate: '+3 days'),
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'scheduled_to' => fake()->dateTime(max: '-1 day'),
        ]);
    }
}
