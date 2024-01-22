<?php

declare(strict_types=1);

namespace Modules\Auth\Models;

use Database\Factories\BarberFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Barber extends Authenticatable
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'name',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'password' => 'hashed',
        'is_admin' => 'boolean',
    ];

    protected static function newFactory(): BarberFactory
    {
        return new BarberFactory;
    }
}
