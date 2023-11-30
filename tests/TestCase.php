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
        $anotherUser = Customer::factory()->create();

        $this->actingAs($anotherUser)->{$method . 'Json'}($route)->assertNotFound();
    }
}
