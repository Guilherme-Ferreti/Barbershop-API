<?php

declare(strict_types=1);

namespace Database\Factories\Schedules;

use App\Domain\Common\Models\Customer;
use App\Domain\Common\Models\Schedule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Domain\Common\Models\Schedule>
 */
class ScheduleFactory extends Factory
{
    protected $model = Schedule::class;

    public function definition(): array
    {
        return [
            'customer_id'   => Customer::factory(),
            'customer_name' => fn (array $attributes) => Customer::find($attributes['customer_id'])->name,
            'scheduled_to'  => fake()->dateTime(timezone: 'America/Sao_Paulo'),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'scheduled_to' => fake()->dateTimeBetween(startDate: '+1 hour', endDate: '+3 days', timezone: 'America/Sao_Paulo'),
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'scheduled_to' => fake()->dateTime(max: '-1 day', timezone: 'America/Sao_Paulo'),
        ]);
    }
}
