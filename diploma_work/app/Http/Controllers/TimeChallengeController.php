<?php

namespace App\Http\Controllers;

use App\Interfaces\ChallengeInterface;
use App\Models\User;
use App\Models\UserChallenge;
use App\Models\UserPoint;
use Carbon\Carbon;

class TimeChallengeController extends Controller implements ChallengeInterface
{
    public function checkConditions(User $user, UserChallenge $userChallenge): bool
    {
        return match ($userChallenge->challenge->due_type) {
            'daily' => $this->checkDaily($user, $userChallenge),
            'weekly' => $this->checkWeekly($user, $userChallenge),
            'monthly' => $this->checkMonthly($user, $userChallenge),
            default => false,
        };
    }

    public function checkCondition(User $user, UserChallenge $userChallenge, $time): bool
    {
        $challengeTime = Carbon::parse($userChallenge->challenge->time);
        $userTime = Carbon::parse($time);

        if ($userTime->greaterThanOrEqualTo($challengeTime)) {

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
        if ($user->runInformation && !empty($user->runInformation->daily_time)) {
            return $this->checkCondition($user, $userChallenge, $user->runInformation->daily_time);
        }

        return false;
    }

    public function checkWeekly(User $user, UserChallenge $userChallenge): bool
    {
        if ($user->runInformation && !empty($user->runInformation->weekly_time)) {
            return $this->checkCondition($user, $userChallenge, $user->runInformation->weekly_time);
        }

        return false;
    }

    public function checkMonthly(User $user, UserChallenge $userChallenge): bool
    {
        if ($user->runInformation && !empty($user->runInformation->monthly_time)) {
            return $this->checkCondition($user, $userChallenge, $user->runInformation->monthly_time);
        }

        return false;
    }
}
