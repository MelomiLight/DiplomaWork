<?php

namespace App\Repositories;

use App\Models\RunningSession;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Laravel\Sanctum\PersonalAccessToken;

class RunningSessionRepository implements Repository
{

    public function find($id)
    {
        return RunningSession::find($id);
    }

    public function all($user): Collection
    {
//        $token = PersonalAccessToken::findToken($token);
//        $user = $token->tokenable;
        return $user->runningSessions;
    }
}