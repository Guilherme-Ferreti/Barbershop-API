<?php

declare(strict_types=1);

use Domain\Users\Models\User;

use function Pest\Laravel\actingAs;

uses()->group('admin');

test('an user profile can be retrieved', function () {
    $user = User::factory()->create();

    $route = route('api.admin.profile.show');

    assertAuthenticatedOnly($route, 'get');
    assertAdminOnly($route, 'get');

    $response = actingAs($user)->getJson($route)->assertOk();

    expect($response->json())
        ->toHaveKeys([
            'id',
            'name',
        ]);
});
