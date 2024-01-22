<?php

declare(strict_types=1);

namespace Modules\Auth\Models;

use Database\Factories\CustomerFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\Booking\Models\Appointment;

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

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function pendingAppointment(): HasOne
    {
        return $this->appointments()->one()->ofMany([
            'scheduled_to' => 'max',
        ], fn (Builder $query) => $query->pending());
    }
}
