<?php

namespace App\Services;

use App\Http\Requests\RunningSessionRequest;
use App\Models\RunInformation;
use App\Models\RunningSession;
use App\Models\User;
use App\Models\UserPoint;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RunningSessionService
{
    public function create(RunningSessionRequest $request)
    {
        $request->merge(['user_id' => $request->user()->id]);

        return DB::transaction(function () use ($request) {
            $runningSession = RunningSession::create([
                'user_id' => $request->user_id,
                'distance_km' => $request->distance_km,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'total_time' => $request->total_time,
                'average_speed' => $request->average_speed,
                'max_speed' => $request->max_speed,
                'calories_burned' => $request->calories_burned,
                'points' => $request->points,
                'speeds' => $request->speeds,
                'locations' => $request->locations,
            ]);
            $this->addUserPoints($runningSession);
            $this->addRunInformation($runningSession);

            return $runningSession;
        });


    }

    public function addRunInformation(RunningSession $runningSession): void
    {
        $user = User::with('runInformation')->find($runningSession->user_id);

        $distance = round($runningSession->distance_km, 2);
        $calories = round($runningSession->calories_burned, 2);
        $totalTime = $runningSession->total_time;

        if ($user && $user->runInformation) {
            DB::transaction(function () use ($calories, $totalTime, $distance, $user, $runningSession) {
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

                $runInformation->save();
            });
        } else {
            DB::transaction(function () use ($calories, $totalTime, $distance, $runningSession) {
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
            });
        }
    }


    public function addUserPoints(RunningSession $runningSession): void
    {
        $user = User::find($runningSession->user_id);

        DB::transaction(function () use ($user, $runningSession) {
            UserPoint::create([
                'user_id' => $runningSession->user_id,
                'prev_points' => $user->points,
                'earned_points' => $runningSession->points,
                'earned_date' => now(),
            ]);

            $user->points += $runningSession->points;
            $user->save();
        });
    }

    public function remove(RunningSession $runningSession)
    {
        return DB::transaction(function () use ($runningSession) {
            $runningSession->delete();
        });
    }

    private function addTimes(string $time1, string $time2): string
    {

        $time1Carbon = Carbon::createFromTimeString($time1);

        list($hours, $minutes, $seconds) = array_map('intval', explode(':', $time2));

        $newDateTime = $time1Carbon->addHours($hours)->addMinutes($minutes)->addSeconds($seconds);

        // Format the result as H:i:s
        return $newDateTime->toTimeString();
    }

}
