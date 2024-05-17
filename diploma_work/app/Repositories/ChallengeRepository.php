<?php

namespace App\Repositories;

use App\Models\Challenge;
use Illuminate\Database\Eloquent\Collection;

class ChallengeRepository
{
    public function all(): Collection
    {
        return Challenge::all();
    }
}
