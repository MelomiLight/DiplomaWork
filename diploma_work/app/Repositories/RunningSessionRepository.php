<?php

namespace App\Repositories;

use App\Models\RunningSession;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Laravel\Sanctum\PersonalAccessToken;

class RunningSessionRepository
{

    public function find($id)
    {
        return RunningSession::find($id);
    }

    public function all($user): Collection
    {
        return $user->runningSessions;
    }
}
