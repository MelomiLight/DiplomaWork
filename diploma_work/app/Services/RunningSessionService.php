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
        $user = User::with('runInformation')->find($runningSession->user_id);

        $distance = round($runningSession->distance_km, 2);
        $calories = round($runningSession->calories_burned, 2);
        $totalTime = $runningSession->total_time;

        if ($user && $user->runInformation) {
            $runInformation = $user->runInformation;

            $runInformation->daily_distance_km = round($runInformation->daily_distance_km + $distance, 2);
            $runInformation->daily_time = $this->addTimes($runInformation->daily_time, $totalTime);
            $runInformation->daily_calories_burned = round($runInformation->daily_calories_burned + $calories, 2);

            $runInformation->weekly_distance_km = round($runInformation->weekly_distance_km + $distance, 2);
            $runInformation->weekly_time = $this->addTimes($runInformation->weekly_time, $totalTime);
            $runInformation->weekly_calories_burned = round($runInformation->weekly_calories_burned + $calories, 2);

            $runInformation->monthly_distance_km = round($runInformation->monthly_distance_km + $distance, 2);
            $runInformation->monthly_time = $this->addTimes($runInformation->monthly_time, $totalTime);
            $runInformation->monthly_calories_burned = round($runInformation->monthly_calories_burned + $calories, 2);

            $runInformation->total_distance_km = round($runInformation->total_distance_km + $distance, 2);
            $runInformation->total_time = $this->addTimes($runInformation->total_time, $totalTime);
            $runInformation->total_calories_burned = round($runInformation->total_calories_burned + $calories, 2);

            // Save the updated run information
            $runInformation->save();
        } else {
            RunInformation::create([
                'user_id' => $runningSession->user_id,
                'daily_distance_km' => $distance,
                'daily_time' => $totalTime,
                'daily_calories_burned' => $calories,
                'weekly_distance_km' => $distance,
                'weekly_time' => $totalTime,
                'weekly_calories_burned' => $calories,
                'monthly_distance_km' => $distance,
                'monthly_time' => $totalTime,
                'monthly_calories_burned' => $calories,
                'total_distance_km' => $distance,
                'total_time' => $totalTime,
                'total_calories_burned' => $calories,
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
