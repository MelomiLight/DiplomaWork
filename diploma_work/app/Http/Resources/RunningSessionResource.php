<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *      schema="RunningSessionResource",
 *      @OA\Property(property="id", type="integer", example=1),
 *      @OA\Property(property="user_id", type="integer", example=1),
 *      @OA\Property(property="distance_km", type="number", format="float", example=5.5),
 *      @OA\Property(property="start_time", type="string", format="date-time", example="2024-05-24T14:00:00Z"),
 *      @OA\Property(property="end_time", type="string", format="date-time", example="2024-05-24T14:30:00Z"),
 *      @OA\Property(property="total_time", type="integer", example=1800),
 *      @OA\Property(property="average_speed", type="number", format="float", example=10.5),
 *      @OA\Property(property="max_speed", type="number", format="float", example=12.5),
 *      @OA\Property(property="calories_burned", type="integer", example=300),
 *      @OA\Property(property="points", type="integer", example=50)
 * )
 */
class RunningSessionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'user_id'=>$this->user_id,
            'distance_km'=>$this->distance_km,
            'start_time'=>$this->start_time,
            'end_time'=>$this->end_time,
            'total_time'=>$this->total_time,
            'average_speed'=>$this->average_speed,
            'max_speed'=>$this->max_speed,
            'calories_burned'=>$this->calories_burned,
            'points'=>$this->points,
            'speeds' => $this->speeds,
            'locations' => $this->locations,
        ];
    }
}
