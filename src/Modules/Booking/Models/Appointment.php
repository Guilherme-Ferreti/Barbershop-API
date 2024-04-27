<?php

declare(strict_types=1);

namespace Modules\Booking\Models;

use Database\Factories\AppointmentFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Auth\Models\Barber;
use Modules\Auth\Models\Customer;

class Appointment extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'barber_id',
        'customer_id',
        'scheduled_to',
        'customer_name',
    ];

    protected $casts = [
        'scheduled_to' => 'datetime',
    ];

    protected static function newFactory(): AppointmentFactory
    {
        return new AppointmentFactory;
    }

    public function barber(): BelongsTo
    {
        return $this->belongsTo(Barber::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function isPending(): bool
    {
        return $this->scheduled_to->greaterThanOrEqualTo(now()->seconds(0));
    }

    public function scopeWhereDateBetween(Builder $query, string $column, string $from, string $to): void
    {
        $query->whereDate($column, '>=', $from)
            ->whereDate($column, '<=', $to);
    }

    public function scopePending(Builder $query): void
    {
        $query->where('scheduled_to', '>=', now()->seconds(0)->format('Y-m-d H:i:s'));
    }
}
