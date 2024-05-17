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

        if ($request->has('day')) {
            $query->whereDate('earned_date', Carbon::today());
        }

        if ($request->has('week')) {
            $query->whereBetween('earned_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        }

        if ($request->has('month')) {
            $query->whereMonth('earned_date', Carbon::now()->month)
                ->whereYear('earned_date', Carbon::now()->year);
        }

        // Summarize points per user and order by points earned
        $leaderboard = $query->select('user_id', DB::raw('SUM(earned_points) as total_points'))
            ->groupBy('user_id')
            ->orderByDesc('total_points')
            ->with('user')  // eager load user
            ->get();

        return response()->json($leaderboard);
    }
}
