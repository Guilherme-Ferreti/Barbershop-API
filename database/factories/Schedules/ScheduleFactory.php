<?php

declare(strict_types=1);

namespace Database\Factories\Schedules;

use App\Domain\Customers\Models\Customer;
use App\Domain\Schedules\Models\Schedule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Domain\Schedules\Models\Schedule>
 */
class ScheduleFactory extends Factory
{
    protected $model = Schedule::class;

    public function definition(): array
    {
        $customer = Customer::factory()->create();

        return [
            'customer_id'   => $customer->id,
            'customer_name' => $customer->name,
            'scheduled_to'  => $this->faker->dateTime(),
        ];
    }
}
