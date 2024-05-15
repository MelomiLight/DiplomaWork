<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RunInformationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'daily' => [
                'daily_distance_km' => optional($this)->daily_distance_km,
                'daily_time' => optional($this)->daily_time,
                'daily_calories_burned' => optional($this)->daily_calories_burned,
            ],
            'weekly' => [
                'weekly_distance_km' => optional($this)->weekly_distance_km,
                'weekly_time' => optional($this)->weekly_time,
                'weekly_calories_burned' => optional($this)->weekly_calories_burned,
            ],
            'monthly' => [
                'monthly_distance_km' => optional($this)->monthly_distance_km,
                'monthly_time' => optional($this)->monthly_time,
                'monthly_calories_burned' => optional($this)->monthly_calories_burned,
            ],
            'total' => [
                'total_distance_km' => optional($this)->total_distance_km,
                'total_time' => optional($this)->total_time,
                'total_calories_burned' => optional($this)->total_calories_burned,
            ],
        ];
    }
}
