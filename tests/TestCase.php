<?php

declare(strict_types=1);

namespace Tests;

use App\Domain\Common\Models\Customer;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function assertAuthenticatedOnly(string $route, string $method = 'post'): void
    {
        $this->{$method . 'Json'}($route)->assertUnauthorized();
    }

    public function assertOwnerOnly(string $route, string $method = 'post'): void
    {
        $anotherCustomer = Customer::factory()->create();

        $this->actingAs($anotherCustomer)->{$method . 'Json'}($route)->assertNotFound();
    }

    public function assertAdminOnly(string $route, string $method = 'post'): void
    {
        $customer = Customer::factory()->create();

        $this->actingAs($customer)->{$method . 'Json'}($route)->assertUnauthorized();
    }
}
