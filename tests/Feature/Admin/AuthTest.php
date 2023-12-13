<?php

declare(strict_types=1);

use App\Domain\Admin\Actions\Login;
use App\Domain\Admin\Data\Actions\LoginData;
use App\Domain\Authenticated\Actions\Login as CustomerLogin;
use App\Domain\Authenticated\Data\Actions\LoginData as CustomerLoginData;
use App\Domain\Common\Models\Customer;
use App\Models\User;

use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

uses()->group('admin');

test('an admin can login', function () {
    $admin = User::factory()->create();

    $payload = [
        'password' => 'password',
    ];

    $response = postJson(route('admin.login'), $payload)->assertOk();

    expect($response->json())
        ->toHaveKeys([
            'accessToken',
            'user' => [
                'id',
                'name',
            ],
        ])
        ->user->id->toBe($admin->id)
        ->user->name->toBe($admin->name);
});

test('an admin cannot login using non-existing password', function () {
    User::factory(10)->create();

    $payload = [
        'password' => fake()->words(asText: true),
    ];

    postJson(route('admin.login'), $payload)->assertUnprocessable();
});

test('jwt authentication works', function () {
    User::factory()->create();

    [, $jwt] = app(Login::class)->handle(new LoginData('password'));

    $route = route('admin.profile.show');

    getJson($route)->assertUnauthorized();

    getJson($route, ['Authorization' => "Bearer $jwt"])->assertOk();
});

test('only an admin can access administrative routes', function () {
    $customer = Customer::factory()->create();

    [, $jwt] = app(CustomerLogin::class)->handle(new CustomerLoginData($customer->phone_number));

    $route = route('admin.profile.show');

    getJson($route, ['Authorization' => "Bearer $jwt"])->assertUnauthorized();
});
