<?php

declare(strict_types=1);

namespace Domain\Customers\Models;

use Database\Factories\Customers\CustomerFactory;
use Domain\Schedules\Models\Schedule;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
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

    public function pendingSchedule(): HasOne
    {
        return $this->schedules()->one()->ofMany([
            'scheduled_to' => 'max',
        ], fn (Builder $query) => $query->pending());
    }
}
