<?php

declare(strict_types=1);

return [
    'secret_key' => env('JWT_SECRET_KEY', ''),

    'expires_in' => env('JWT_EXPIRES_IN', 1),
];
