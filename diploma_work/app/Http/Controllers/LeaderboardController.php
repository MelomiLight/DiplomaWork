<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\UserPoint;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Get the leaderboard based on the specified time period.
 *
 * @param Request $request
 * @return JsonResponse
 *
 * @OA\Get(
 *      path="/api/leaderboard",
 *      summary="Get the leaderboard",
 *      tags={"Leaderboard"},
 *      @OA\Parameter(
 *          name="Accept",
 *          in="header",
 *          required=true,
 *          example="application/json"
 *      ),
 *      @OA\Parameter(
 *          name="Authorization",
 *          in="header",
 *          required=true,
 *          example="Bearer your_token"
 *      ),
 *      @OA\Parameter(
 *          name="value",
 *          in="query",
 *          required=true,
 *          @OA\Schema(type="string", enum={"day", "week", "month", "all"}),
 *          description="Time period for leaderboard"
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="OK",
 *          @OA\JsonContent(
 *              type="array",
 *              @OA\Items(
 *                  @OA\Property(property="total_points", type="integer", example=150),
 *                  @OA\Property(property="total_distance", type="string", example="20.5"),
 *                  @OA\Property(property="user", ref="#/components/schemas/UserResource")
 *              )
 *          )
 *      ),
 *      @OA\Response(
 *          response=400,
 *          description="Invalid value parameter",
 *          @OA\JsonContent(
 *              @OA\Property(property="error", type="string", example="invalid value parameter")
 *          )
 *      ),
 *      @OA\Response(
 *          response=500,
 *          description="Internal Server Error"
 *      )
 * )
 */
class LeaderboardController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = UserPoint::query();
        $value = $request->query('value');

        if (!in_array($value, ['day', 'week', 'month', 'all'])) {
            return response()->json(["error" => __('messages.value_error')], 400);
        }

        switch ($value) {
            case 'day':
                $query->whereDate('earned_date', Carbon::today());
                break;
            case 'week':
                $query->whereBetween('earned_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('earned_date', Carbon::now()->month)
                    ->whereYear('earned_date', Carbon::now()->year);
                break;
            default:
                break;
        }

        // Summarize points per user and order by points earned
        $leaderboard = $query->select('user_id', DB::raw('SUM(earned_points) as total_points'))
            ->groupBy('user_id')
            ->orderByDesc('total_points')
            ->with(['user' => function ($query) use ($value) {
                $query->with(['runInformation' => function ($query) use ($value) {
                    switch ($value) {
                        case 'day':
                            $query->select('user_id', 'daily_distance_km as distance');
                            break;
                        case 'week':
                            $query->select('user_id', 'weekly_distance_km as distance');
                            break;
                        case 'month':
                            $query->select('user_id', 'monthly_distance_km as distance');
                            break;
                        default:
                            $query->select('user_id', 'total_distance_km as distance');
                            break;
                    }
                }]);
            }])
            ->get();

        // Transform the data
        $result = $leaderboard->map(function ($userPoint) {
            return [
                'total_points' => $userPoint->total_points,
                'total_distance' => (string)round($userPoint->user->runInformation->distance ?? 0, 2),
                'user' => $userPoint->user,
            ];
        });

        return response()->json(['data' => $result]);
    }
}
