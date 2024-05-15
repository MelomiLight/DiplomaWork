<?php

namespace App\Http\Resources;

use App\Models\RunningSession;
use App\Services\ImageService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Nette\Utils\Image;

class UserResource extends JsonResource
{
    protected $token;

    public function __construct($resource)
    {
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     * @throws Exception
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
            'running_sessions'=>RunningSessionResource::collection($this->runningSessions),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

}
