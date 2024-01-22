<?php

declare(strict_types=1);

use Modules\Auth\Models\Barber;

use function Pest\Laravel\actingAs;

uses()->group('admin');

test('a barber profile can be retrieved', function () {
    $barber = Barber::factory()->create();

    $response = actingAs($barber)->getJson(route('api.admin.profile.show'))->assertOk();

    expect($response->json())
        ->toHaveKeys([
            'id',
            'name',
        ]);
});
