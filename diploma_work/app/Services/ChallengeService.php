<?php

namespace App\Services;

use App\Http\Requests\ChallengeRequest;
use App\Models\Challenge;
use App\Models\User;
use App\Models\UserChallenge;
use App\Models\UserPoint;
use Illuminate\Support\Facades\DB;

class ChallengeService
{
    public function checkCondition(User $user, UserChallenge $userChallenge, $distance): bool
    {
        if ($distance >= $userChallenge->challenge->distance_km) {
            $userChallenge->challenge_status = true;
            $user->points += $userChallenge->challenge->points;

            UserPoint::create([
                'user_id' => $user->id,
                'prev_points' => $user->points,
                'earned_points' => $userChallenge->challenge->points,
                'earned_date' => now(),
            ]);

            $user->save();
            $userChallenge->save();

            return true;
        }

        return false;
    }

    public function checkDaily(User $user, UserChallenge $userChallenge): bool
    {
        return $this->checkCondition($user, $userChallenge, $user->runInformation->daily_distance_km);
    }

    public function checkWeekly(User $user, UserChallenge $userChallenge): bool
    {
        return $this->checkCondition($user, $userChallenge, $user->runInformation->weekly_distance_km);
    }

    public function checkMonthly(User $user, UserChallenge $userChallenge): bool
    {
        return $this->checkCondition($user, $userChallenge, $user->runInformation->monthly_distance_km);
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

