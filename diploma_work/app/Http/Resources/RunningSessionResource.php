<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'user_id'=>$this->user_id,
            'distance_km'=>$this->distance_km,
            'start_time'=>$this->start_time,
            'end_time'=>$this->end_time,
            'total_time'=>$this->total_time,
            'average_speed'=>$this->average_speed,
            'max_speed'=>$this->max_speed,
            'calories_burned'=>$this->calories_burned,
            'points'=>$this->points,
        ];
    }
}
