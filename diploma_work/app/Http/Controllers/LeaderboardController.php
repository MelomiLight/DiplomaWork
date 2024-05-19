<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\UserPoint;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LeaderboardController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = UserPoint::query();
        $value = $request->query('value');

        if ($value === 'day') {
            $query->whereDate('earned_date', Carbon::today());
        } elseif ($value === 'week') {
            $query->whereBetween('earned_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($value === 'month') {
            $query->whereMonth('earned_date', Carbon::now()->month)
                ->whereYear('earned_date', Carbon::now()->year);
        } elseif ($value === 'all') {
            // No date filter for 'all'
        } else {
            return response()->json(["error" => "invalid value parameter"], 400);
        }

        // Summarize points per user and order by points earned
        $leaderboard = $query->select('user_id', DB::raw('SUM(earned_points) as total_points'))
            ->groupBy('user_id')
            ->orderByDesc('total_points')
            ->with(['user' => function ($query) use ($value) {
                $query->with(['runInformation' => function ($query) use ($value) {
                    if ($value === 'day') {
                        $query->select('user_id', 'daily_distance_km as distance');
                    } elseif ($value === 'week') {
                        $query->select('user_id', 'weekly_distance_km as distance');
                    } elseif ($value === 'month') {
                        $query->select('user_id', 'monthly_distance_km as distance');
                    } elseif ($value === 'all') {
                        // Assuming there is a total distance field for all time
                        $query->select('user_id', 'total_distance_km as distance');
                    }
                }]);
            }])
            ->get();

        // Transform the data
        $result = $leaderboard->map(function ($userPoint) {
            return [
                'total_points' => $userPoint->total_points,
                'total_distance' => (string) ($userPoint->user->runInformation->distance ?? 0),
                'user' => $userPoint->user,
            ];
        });

        return response()->json(['data' => $result]);
    }
}
