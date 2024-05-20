<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ScheduleService
{
    public function initializeUsers(): void
    {
        try {
            DB::transaction(function () {
                $users = DB::table('users')->where('initialized', false)->get();
                $challenges = DB::table('challenges')->get();

                foreach ($users as $user) {
                    $dailyChallenges = $challenges->where('due_type', 'daily')->random(2);
                    foreach ($dailyChallenges as $challenge) {
                        DB::table('user_challenges')->insert([
                            'user_id' => $user->id,
                            'challenge_id' => $challenge->id,
                            'challenge_status' => false,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }

                    $weeklyChallenges = $challenges->where('due_type', 'weekly')->random(2);
                    foreach ($weeklyChallenges as $challenge) {
                        DB::table('user_challenges')->insert([
                            'user_id' => $user->id,
                            'challenge_id' => $challenge->id,
                            'challenge_status' => false,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }


                    $monthlyChallenges = $challenges->where('due_type', 'monthly')->random(2);
                    foreach ($monthlyChallenges as $challenge) {
                        DB::table('user_challenges')->insert([
                            'user_id' => $user->id,
                            'challenge_id' => $challenge->id,
                            'challenge_status' => false,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }


                    DB::table('users')->where('id', $user->id)->update(['initialized' => true]);
                }
            });
            Log::info('initializeUsers executed successfully.');
        } catch (\Exception $e) {
            Log::error('Error in initializeUsers: ' . $e->getMessage());
        }
    }

    public function updateDailyChallenges(): void
    {
        try {
            DB::transaction(function () {
                $users = DB::table('users')->get();
                $challenges = DB::table('challenges')->where('due_type', 'daily')->get();

                foreach ($users as $user) {

                    $existingDailyChallenges = DB::table('user_challenges')
                        ->join('challenges', 'user_challenges.challenge_id', '=', 'challenges.id')
                        ->where('user_challenges.user_id', $user->id)
                        ->where('challenges.due_type', 'daily')
                        ->select('user_challenges.id')
                        ->get();

                    foreach ($existingDailyChallenges as $existingChallenge) {
                        DB::table('user_challenges')->where('id', $existingChallenge->id)->delete();
                    }

                    $newDailyChallenges = $challenges->random(2);
                    foreach ($newDailyChallenges as $challenge) {
                        DB::table('user_challenges')->insert([
                            'user_id' => $user->id,
                            'challenge_id' => $challenge->id,
                            'challenge_status' => false,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }


                    DB::table('run_information')->where('user_id', $user->id)->update([
                        'daily_distance_km' => 0,
                        'daily_time' => '00:00:00',
                        'daily_calories_burned' => 0,
                        'updated_at' => now()
                    ]);
                }
            });
            Log::info('updateDailyChallenges executed successfully.');
        } catch (\Exception $e) {
            Log::error('Error in updateDailyChallenges: ' . $e->getMessage());
        }
    }

    public function updateWeeklyChallenges(): void
    {
        try {
            DB::transaction(function () {
                $users = DB::table('users')->get();
                $challenges = DB::table('challenges')->where('due_type', 'weekly')->get();

                foreach ($users as $user) {

                    $existingWeeklyChallenges = DB::table('user_challenges')
                        ->join('challenges', 'user_challenges.challenge_id', '=', 'challenges.id')
                        ->where('user_challenges.user_id', $user->id)
                        ->where('challenges.due_type', 'weekly')
                        ->select('user_challenges.id')
                        ->get();

                    foreach ($existingWeeklyChallenges as $existingChallenge) {
                        DB::table('user_challenges')->where('id', $existingChallenge->id)->delete();
                    }

                    $newWeeklyChallenges = $challenges->random(2);
                    foreach ($newWeeklyChallenges as $challenge) {
                        DB::table('user_challenges')->insert([
                            'user_id' => $user->id,
                            'challenge_id' => $challenge->id,
                            'challenge_status' => false,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }


                    DB::table('run_information')->where('user_id', $user->id)->update([
                        'weekly_distance_km' => 0,
                        'weekly_time' => '00:00:00',
                        'weekly_calories_burned' => 0,
                        'updated_at' => now()
                    ]);
                }
            });
            Log::info('updateWeeklyChallenges executed successfully.');
        } catch (\Exception $e) {
            Log::error('Error in updateWeeklyChallenges: ' . $e->getMessage());
        }
    }

    public function updateMonthlyChallenges(): void
    {
        try {
            DB::transaction(function () {
                $users = DB::table('users')->get();
                $challenges = DB::table('challenges')->where('due_type', 'monthly')->get();

                foreach ($users as $user) {

                    $existingMonthlyChallenges = DB::table('user_challenges')
                        ->join('challenges', 'user_challenges.challenge_id', '=', 'challenges.id')
                        ->where('user_challenges.user_id', $user->id)
                        ->where('challenges.due_type', 'monthly')
                        ->select('user_challenges.id')
                        ->get();

                    foreach ($existingMonthlyChallenges as $existingChallenge) {
                        DB::table('user_challenges')->where('id', $existingChallenge->id)->delete();
                    }

                    $newMonthlyChallenges = $challenges->random(2);
                    foreach ($newMonthlyChallenges as $challenge) {
                        DB::table('user_challenges')->insert([
                            'user_id' => $user->id,
                            'challenge_id' => $challenge->id,
                            'challenge_status' => false,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }


                    DB::table('run_information')->where('user_id', $user->id)->update([
                        'monthly_distance_km' => 0,
                        'monthly_time' => '00:00:00',
                        'monthly_calories_burned' => 0,
                        'updated_at' => now()
                    ]);
                }
            });
            Log::info('updateMonthlyChallenges executed successfully.');
        } catch (\Exception $e) {
            Log::error('Error in updateMonthlyChallenges: ' . $e->getMessage());
        }
    }
}
