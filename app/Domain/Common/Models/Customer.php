<?php

declare(strict_types=1);

namespace App\Domain\Common\Models;

use Database\Factories\Customers\CustomerFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'phone_number',
        'name',
    ];

    protected static function newFactory(): CustomerFactory
    {
        return new CustomerFactory;
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }
}
