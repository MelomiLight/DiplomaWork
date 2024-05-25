<?php

namespace App\Services;

use App\Http\Requests\RunningSessionRequest;
use App\Models\RunInformation;
use App\Models\RunningSession;
use App\Models\User;
use App\Models\UserPoint;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonInterval;

class RunningSessionService
{
    public function create(RunningSessionRequest $request)
    {
        $request->merge(['user_id' => $request->user()->id]);

        return DB::transaction(function () use ($request) {
            return RunningSession::create($request->all());
        });
    }

    public function addRunInformation(RunningSession $runningSession): void
    {
        // Retrieve the user with their run information
        $user = User::with('runInformation')->find($runningSession->user_id);

        if ($user && $user->runInformation) {
            $runInformation = $user->runInformation;

            $runInformation->daily_distance_km += $runningSession->distance_km;
            $runInformation->daily_time = $this->addTimes($runInformation->daily_time, $runningSession->total_time);
            $runInformation->daily_calories_burned += $runningSession->calories_burned;

            $runInformation->weekly_distance_km += $runningSession->distance_km;
            $runInformation->weekly_time = $this->addTimes($runInformation->weekly_time, $runningSession->total_time);
            $runInformation->weekly_calories_burned += $runningSession->calories_burned;

            $runInformation->monthly_distance_km += $runningSession->distance_km;
            $runInformation->monthly_time = $this->addTimes($runInformation->monthly_time, $runningSession->total_time);
            $runInformation->monthly_calories_burned += $runningSession->calories_burned;

            $runInformation->total_distance_km += $runningSession->distance_km;
            $runInformation->total_time = $this->addTimes($runInformation->total_time, $runningSession->total_time);
            $runInformation->total_calories_burned += $runningSession->calories_burned;

            // Save the updated run information
            $runInformation->save();
        } else {
            RunInformation::create([
                'user_id' => $runningSession->user_id,
                'daily_distance_km' => $runningSession->distance_km,
                'daily_time' => $runningSession->total_time,
                'daily_calories_burned' => $runningSession->calories_burned,
                'weekly_distance_km' => $runningSession->distance_km,
                'weekly_time' => $runningSession->total_time,
                'weekly_calories_burned' => $runningSession->calories_burned,
                'monthly_distance_km' => $runningSession->distance_km,
                'monthly_time' => $runningSession->total_time,
                'monthly_calories_burned' => $runningSession->calories_burned,
                'total_distance_km' => $runningSession->distance_km,
                'total_time' => $runningSession->total_time,
                'total_calories_burned' => $runningSession->calories_burned,
            ]);
        }
    }

    public function addUserPoints(RunningSession $runningSession): void
    {
        $user = User::find($runningSession->user_id);

        UserPoint::create([
            'user_id' => $runningSession->user_id,
            'prev_points' => $user->points,
            'earned_points' => $runningSession->points,
            'earned_date' => now(),
        ]);

        $user->points += $runningSession->points;
        $user->save();
    }

    public function remove(RunningSession $runningSession)
    {
        return DB::transaction(function () use ($runningSession) {
            $runningSession->delete();
        });
    }

    private function addTimes($time1, $time2): string
    {
        $time1 = $time1 ?: '00:00:00';
        $time2 = $time2 ?: '00:00:00';

        $time1 = Carbon::createFromFormat('H:i:s', $time1);
        $time2 = Carbon::createFromFormat('H:i:s', $time2);

        $totalSeconds = $time1->secondsSinceMidnight() + $time2->secondsSinceMidnight();
        $totalTime = CarbonInterval::seconds($totalSeconds)->cascade();

        return $totalTime->format('%H:%I:%S');
    }
}
