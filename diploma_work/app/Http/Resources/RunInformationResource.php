<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *      schema="RunInformationResource",
 *      @OA\Property(
 *          property="daily",
 *          type="object",
 *          @OA\Property(property="daily_distance_km", type="number", format="float", example=5.5),
 *          @OA\Property(property="daily_time", type="string", example="01:30:00"),
 *          @OA\Property(property="daily_calories_burned", type="integer", example=300)
 *      ),
 *      @OA\Property(
 *          property="weekly",
 *          type="object",
 *          @OA\Property(property="weekly_distance_km", type="number", format="float", example=20.0),
 *          @OA\Property(property="weekly_time", type="string", example="05:00:00"),
 *          @OA\Property(property="weekly_calories_burned", type="integer", example=1200)
 *      ),
 *      @OA\Property(
 *          property="monthly",
 *          type="object",
 *          @OA\Property(property="monthly_distance_km", type="number", format="float", example=80.0),
 *          @OA\Property(property="monthly_time", type="string", example="20:00:00"),
 *          @OA\Property(property="monthly_calories_burned", type="integer", example=4800)
 *      ),
 *      @OA\Property(
 *          property="total",
 *          type="object",
 *          @OA\Property(property="total_distance_km", type="number", format="float", example=200.0),
 *          @OA\Property(property="total_time", type="string", example="50:00:00"),
 *          @OA\Property(property="total_calories_burned", type="integer", example=12000)
 *      )
 * )
 */
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
