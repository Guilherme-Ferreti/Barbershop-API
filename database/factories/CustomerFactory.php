<?php

declare(strict_types=1);

namespace Database\Factories;

use Domain\Customers\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Domain\Customers\Models\Customer>
 */
class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'name'         => $this->faker->name(),
            'phone_number' => '55' . preg_replace('/[^0-9]/', '', $this->faker->unique()->phoneNumber()),
        ];
    }
}
