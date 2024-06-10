<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class RunInformation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'daily_distance_km',
        'daily_time',
        'daily_calories_burned',
        'weekly_distance_km',
        'weekly_time',
        'weekly_calories_burned',
        'monthly_distance_km',
        'monthly_time',
        'monthly_calories_burned',
        'total_distance_km',
        'total_time',
        'total_calories_burned'
    ];

    protected $casts = [
        'daily_time' => 'datetime:H:i:s',
        'weekly_time' => 'datetime:H:i:s',
        'monthly_time' => 'datetime:H:i:s',
        'total_time' => 'datetime:H:i:s',
    ];
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
