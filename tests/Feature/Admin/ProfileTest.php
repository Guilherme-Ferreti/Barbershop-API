<?php

declare(strict_types=1);

use App\Models\User;

use function Pest\Laravel\actingAs;

uses()->group('admin');

test('an admin profile can be retrieved', function () {
    $admin = User::factory()->create();

    $route = route('admin.profile.show');

    $this->assertAuthenticatedOnly($route, 'get');
    $this->assertAdminOnly($route, 'get');

    $response = actingAs($admin)->getJson($route)->assertOk();

    expect($response->json())
        ->toHaveKeys([
            'id',
            'name',
        ]);
});
