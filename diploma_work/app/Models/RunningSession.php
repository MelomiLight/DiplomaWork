<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class RunningSession extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    /**
     * Get the user that owns the running session.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
