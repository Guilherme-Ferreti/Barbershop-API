<?php

declare(strict_types=1);

namespace Database\Factories\Customers;

use App\Domain\Customers\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Domain\Customers\Models\Customer>
 */
class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'name'         => $this->faker->name(),
            'phone_number' => preg_replace('/[^0-9]/', '', $this->faker->unique()->phoneNumber()),
        ];
    }
}
