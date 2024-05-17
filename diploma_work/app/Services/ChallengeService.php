<?php

namespace App\Services;

use App\Http\Requests\ChallengeRequest;
use App\Models\Challenge;
use App\Models\User;
use App\Models\UserChallenge;
use Illuminate\Support\Facades\DB;

class ChallengeService
{
    public function checkDaily(User $user, UserChallenge $userChallenge): bool
    {
        if ($user->runInformation->daily_distance_km >= $userChallenge->challenge->distance_km) {
            $userChallenge->challenge_status = true;
            $user->points += $userChallenge->challenge->points;
            $user->save();
            $userChallenge->save();

            return true;
        }

        return false;
    }

    public function checkWeekly(User $user, UserChallenge $userChallenge): bool
    {
        if ($user->runInformation->weekly_distance_km >= $userChallenge->challenge->distance_km) {
            $userChallenge->challenge_status = true;
            $user->points += $userChallenge->challenge->points;
            $user->save();
            $userChallenge->save();

            return true;
        }

        return false;
    }

    public function checkMonthly(User $user, UserChallenge $userChallenge): bool
    {
        if ($user->runInformation->monthly_distance_km >= $userChallenge->challenge->distance_km) {
            $userChallenge->challenge_status = true;
            $user->points += $userChallenge->challenge->points;
            $user->save();
            $userChallenge->save();

            return true;
        }

        return false;
    }

    public function create(ChallengeRequest $request)
    {
        return DB::transaction(function () use ($request) {
            return Challenge::create($request->validated());
        });
    }

    public function remove(Challenge $challenge)
    {
        return DB::transaction(function () use ($challenge) {
            $challenge->delete();
        });
    }
}

