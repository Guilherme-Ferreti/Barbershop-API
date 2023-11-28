<?php

declare(strict_types=1);

use App\Domain\Common\Rules\BrazilianPhoneNumber;
use Illuminate\Support\Facades\Validator;

uses()->group('validation-rules');

test('BrazilianPhoneNumber rule validates phone numbers correctly', function (string $phoneNumber) {
    $validator = Validator::make(compact('phoneNumber'), [
        'phoneNumber' => new BrazilianPhoneNumber,
    ]);

    expect($validator->passes())->toBeTrue();

})->with([
    '5511912345678',
    '5511998745632',
    '551112345678',
    '551198745632',
]);

test('BrazilianPhoneNumber rule invalidates phone numbers correctly', function (string $phoneNumber) {
    $validator = Validator::make(compact('phoneNumber'), [
        'phoneNumber' => new BrazilianPhoneNumber,
    ]);

    expect($validator->passes())->toBeFalse();
})->with([
    '1',
    '55',
    '55912345678',
    '55119123456781111',
]);
