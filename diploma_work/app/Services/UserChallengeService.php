<?php

namespace App\Services;

use App\Http\Requests\RunningSessionRequest;
use App\Http\Requests\UserChallengeRequest;
use App\Models\RunningSession;
use App\Models\User;
use App\Models\UserChallenge;
use Illuminate\Support\Facades\DB;

class UserChallengeService
{
    public function remove(User $user): void
    {
        foreach ($user->userChallenges as $userChallenge){
            DB::transaction(function () use ($userChallenge) {
                $userChallenge->delete();
            });
        }
    }

    public function create(UserChallengeRequest $request)
    {
        return DB::transaction(function () use ($request) {
            return UserChallenge::create($request->validated());
        });
    }
}
