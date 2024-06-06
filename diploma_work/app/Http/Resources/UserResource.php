<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    protected $token;

    public function __construct($resource)
    {
        parent::__construct($resource);
    }

    /**
     * @OA\Schema(
     *      schema="UserResource",
     *      @OA\Property(property="id", type="integer", example=1),
     *      @OA\Property(property="name", type="string", example="test"),
     *      @OA\Property(property="email", type="string", example="example@example.com"),
     *      @OA\Property(property="profile_picture", type="string", example="profile_pictures/adagasf21412tgweg"),
     *      @OA\Property(property="weight_kg", type="float", example=62.5),
     *      @OA\Property(property="height_cm", type="float", example=181),
     *      @OA\Property(property="points", type="integer", example=50),
     *      @OA\Property(property="running_sessions", type="array", @OA\Items(ref="#/components/schemas/RunningSessionResource")),
     *      @OA\Property(property="created_at", type="string", format="date-time", example="2024-05-24T14:00:00Z"),
     *      @OA\Property(property="updated_at", type="string", format="date-time", example="2024-05-24T14:00:00Z")
     * )
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'profile_picture' => $this->profile_picture,
            'weight_kg' => $this->weight_kg,
            'height_cm' => $this->height_cm,
            'points' => $this->points,
            'running_sessions' => RunningSessionResource::collection($this->runningSessions),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'isMale' => $this->isMale,
            'birthDate' => $this->birthDate,
            'fitPercentage' => $this->firPercentage,
        ];
    }

}
