<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class ScheduleService
{
    public function initializeUsers(): void
    {
        DB::transaction(function () {
            $users = DB::table('users')->where('initialized', false)->get();
            $challenges = DB::table('challenges')->get();

            foreach ($users as $user) {
                // Assign 2 daily challenges
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

                // Assign 2 weekly challenges
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

                // Assign 2 monthly challenges
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

                // Mark user as initialized
                DB::table('users')->where('id', $user->id)->update(['initialized' => true]);
            }
        });
    }

    public function updateDailyChallenges(): void
    {
        DB::transaction(function () {
            $users = DB::table('users')->get();
            $challenges = DB::table('challenges')->where('due_type', 'daily')->get();

            foreach ($users as $user) {
                // Reassign 2 daily challenges
                $existingDailyChallenges = DB::table('user_challenges')
                    ->where('user_id', $user->id)
                    ->whereHas('challenge', function ($query) {
                        $query->where('due_type', 'daily');
                    })
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

                // Reset daily run information
                DB::table('run_information')->where('user_id', $user->id)->update([
                    'daily_distance_km' => 0,
                    'daily_time' => '00:00:00',
                    'daily_calories_burned' => 0,
                    'updated_at' => now()
                ]);
            }
        });
    }

    public function updateWeeklyChallenges(): void
    {
        DB::transaction(function () {
            $users = DB::table('users')->get();
            $challenges = DB::table('challenges')->where('due_type', 'weekly')->get();

            foreach ($users as $user) {
                // Reassign 2 weekly challenges
                $existingWeeklyChallenges = DB::table('user_challenges')
                    ->where('user_id', $user->id)
                    ->whereHas('challenge', function ($query) {
                        $query->where('due_type', 'weekly');
                    })
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

                // Reset weekly run information
                DB::table('run_information')->where('user_id', $user->id)->update([
                    'weekly_distance_km' => 0,
                    'weekly_time' => '00:00:00',
                    'weekly_calories_burned' => 0,
                    'updated_at' => now()
                ]);
            }
        });
    }

    public function updateMonthlyChallenges(): void
    {
        DB::transaction(function () {
            $users = DB::table('users')->get();
            $challenges = DB::table('challenges')->where('due_type', 'monthly')->get();

            foreach ($users as $user) {
                // Reassign 2 monthly challenges
                $existingMonthlyChallenges = DB::table('user_challenges')
                    ->where('user_id', $user->id)
                    ->whereHas('challenge', function ($query) {
                        $query->where('due_type', 'monthly');
                    })
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

                // Reset monthly run information
                DB::table('run_information')->where('user_id', $user->id)->update([
                    'monthly_distance_km' => 0,
                    'monthly_time' => '00:00:00',
                    'monthly_calories_burned' => 0,
                    'updated_at' => now()
                ]);
            }
        });
    }
}
