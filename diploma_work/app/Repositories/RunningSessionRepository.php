<?php

namespace App\Repositories;

use App\Models\RunningSession;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class RunningSessionRepository implements Repository
{

    public function find($id)
    {
        return RunningSession::find($id);
    }

    public function all($user_id): Collection
    {
        return User::where('id', $user_id)->first()->runningSessions;
    }
}
