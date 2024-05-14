<?php

namespace App\Http\Resources;

use App\Models\RunningSession;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    protected $token;

    public function __construct($resource, $token = null)
    {
        parent::__construct($resource);
        $this->token = $token;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
//        // Initialize arrays to hold categorized challenges
//        $dailyChallenges = [];
//        $weeklyChallenges = [];
//        $monthlyChallenges = [];
//
//        // Categorize challenges based on due_type
//        foreach ($this->userChallenges as $userChallenge) {
//            $challenge = $userChallenge->challenge;
//            if ($challenge) {
//                switch ($challenge->due_type) {
//                    case 'daily':
//                        $dailyChallenges[] = new ChallengeResource($challenge);
//                        break;
//                    case 'weekly':
//                        $weeklyChallenges[] = new ChallengeResource($challenge);
//                        break;
//                    case 'monthly':
//                        $monthlyChallenges[] = new ChallengeResource($challenge);
//                        break;
//                }
//            }
//
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'profile_picture' => $this->profile_picture,
            'weight_kg' => $this->weight_kg,
            'height_cm' => $this->height_cm,
            'points' => $this->points,
//            'challenges' => [
//                'daily' => $dailyChallenges,
//                'weekly' => $weeklyChallenges,
//                'monthly' => $monthlyChallenges,
//            ],
            'daily'=>[
                'daily_distance_km'=>optional($this->runInformation)->daily_distance_km,
                'daily_time'=>optional($this->runInformation)->daily_time,
                'daily_calories_burned'=>optional($this->runInformation)->daily_calories_burned,
            ],
            'weekly'=>[
                'weekly_distance_km'=>optional($this->runInformation)->weekly_distance_km,
                'weekly_time'=>optional($this->runInformation)->weekly_time,
                'weekly_calories_burned'=>optional($this->runInformation)->weekly_calories_burned,
            ],
            'monthly'=>[
                'monthly_distance_km'=>optional($this->runInformation)->monthly_distance_km,
                'monthly_time'=>optional($this->runInformation)->monthly_time,
                'monthly_calories_burned'=>optional($this->runInformation)->monthly_calories_burned,
            ],
            'total'=>[
                'total_distance_km'=>optional($this->runInformation)->total_distance_km,
                'total_time'=>optional($this->runInformation)->total_time,
                'total_calories_burned'=>optional($this->runInformation)->total_calories_burned,
            ],
            'running_sessions'=>RunningSessionResource::collection($this->runningSessions),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @param Request $request
     * @return array
     */
    public function with(Request $request): array
    {
        return [
            'token' => $this->token,
        ];
    }
}
