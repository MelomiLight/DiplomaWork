<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *      schema="ChallengeResource",
 *      @OA\Property(property="id", type="integer", example=1),
 *      @OA\Property(property="title", type="string", example="10k Run Challenge"),
 *      @OA\Property(property="description", type="string", example="Complete a 10km run within a month."),
 *      @OA\Property(property="due_type", type="string", example="monthly"),
 *      @OA\Property(property="challenge_type", type="string", example="distanceChallenge"),
 *      @OA\Property(property="is_active", type="boolean", example=true),
 *      @OA\Property(property="points", type="integer", example=100)
 * )
 */
class ChallengeResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'due_type'=>$this->due_type,
            'challenge_type'=>$this->challenge_type,
            'is_active'=>$this->is_active,
            'points'=>$this->points,
        ];
    }
}
