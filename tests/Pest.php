<?php

declare(strict_types=1);

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Modules\Auth\Models\Customer;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\json;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

uses(
    Tests\TestCase::class,
    Illuminate\Foundation\Testing\RefreshDatabase::class,
)
    ->beforeEach(fn () => Http::fake())
    ->in('Feature', 'Unit');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeDateFormat', function (string $format) {
    $date = Carbon::tryCreateFromFormat($format, $this->value);

    expect($date)->not()->toBeNull('Value is not compatible with format ' . $format);

    return $this;
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function assertAuthenticatedOnly(string $route, string $method = 'post'): void
{
    json($method, $route)->assertUnauthorized();
}

function assertOwnerOnly(string $route, string $method = 'post'): void
{
    $anotherCustomer = Customer::factory()->create();

    actingAs($anotherCustomer)->{$method . 'Json'}($route)->assertNotFound();
}
