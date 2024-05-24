<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *      schema="UserChallengeResource",
 *      @OA\Property(property="id", type="integer", example=1),
 *      @OA\Property(property="user_id", type="integer", example=1),
 *      @OA\Property(property="challenge", ref="#/components/schemas/ChallengeResource"),
 *      @OA\Property(property="challenge_status", type="boolean", example=true)
 * )
 */
class UserChallengeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'challenge' => new ChallengeResource($this->challenge),
            'challenge_status' => $this->challenge_status,
        ];
    }
}
