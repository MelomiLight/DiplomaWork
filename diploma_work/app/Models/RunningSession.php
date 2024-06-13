<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class RunningSession extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'distance_km',
        'start_time',
        'end_time',
        'total_time',
        'average_speed',
        'max_speed',
        'calories_burned',
        'points',
        'speeds',
        'locations'
    ];
    protected $casts = [
        'speeds' => 'array',
        'locations' => 'array'
    ];

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * Get the user that owns the running session.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
