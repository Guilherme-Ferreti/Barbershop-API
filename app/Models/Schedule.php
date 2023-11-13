<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'customer_id',
        'scheduled_to',
        'customer_name',
    ];

    protected $casts = [
        'scheduled_to' => 'datetime',
    ];
}
