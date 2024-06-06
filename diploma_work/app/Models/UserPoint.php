<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'prev_points',
        'earned_points',
        'earned_date'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
