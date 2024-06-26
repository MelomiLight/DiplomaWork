<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'reset_code',
        'profile_picture',
        'weight_kg',
        'height_cm',
        'points',
        'initialized',
        'isMale',
        'birthDate',
        'fitPercentage',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected static function booted(): void
    {
        static::deleting(function ($user) {
            Storage::delete($user->profile_picture);
        });
    }
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function userPoints(): HasMany
    {
        return $this->hasMany(UserPoint::class);
    }

    public function userChallenges(): HasMany
    {
        return $this->hasMany(UserChallenge::class);
    }

    public function runningSessions(): HasMany
    {
        return $this->hasMany(RunningSession::class);
    }

    public function runInformation(): HasOne
    {
        return $this->hasOne(RunInformation::class);
    }
}
