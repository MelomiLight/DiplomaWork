<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserChallengeRequest;
use App\Http\Resources\UserChallengeResource;
use App\Models\User;
use App\Services\UserChallengeService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserChallengeController extends Controller
{
    private UserChallengeService $service;

    public function __construct(UserChallengeService $challengeService)
    {
        $this->service = $challengeService;
    }

    /**
     * Store a new user challenge.
     *
     * @param UserChallengeRequest $request
     * @return JsonResponse
     *
     * @OA\Post(
     *      path="/api/user/challenges/store",
     *      summary="Store a new user challenge",
     *      tags={"User Challenges"},
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
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/UserChallengeResource")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Created",
     *          @OA\JsonContent(ref="#/components/schemas/UserChallengeResource")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal Server Error"
     *      )
     * )
     */
    public function store(UserChallengeRequest $request): JsonResponse
    {
        $userChallenge = $this->service->create($request);

        return response()->json($userChallenge, 201);
    }

    /**
     * Show the challenges for a specific user.
     *
     * @param Request $request
     * @return JsonResponse
     *
     * @OA\Get(
     *      path="/api/user/challenges/show",
     *      summary="Show user challenges",
     *      tags={"User Challenges"},
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
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\JsonContent(
     *              @OA\Property(property="daily", type="array", @OA\Items(ref="#/components/schemas/UserChallengeResource")),
     *              @OA\Property(property="weekly", type="array", @OA\Items(ref="#/components/schemas/UserChallengeResource")),
     *              @OA\Property(property="monthly", type="array", @OA\Items(ref="#/components/schemas/UserChallengeResource"))
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not Found"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal Server Error"
     *      )
     * )
     */
    public function show(Request $request): JsonResponse
    {
        $userChallenges = $request->user()->userChallenges;
        $groupedChallenges = $userChallenges->groupBy(function ($item) {
            return $item->challenge->due_type;
        });

        $response = [
            'daily' => UserChallengeResource::collection($groupedChallenges->get('daily', collect())),
            'weekly' => UserChallengeResource::collection($groupedChallenges->get('weekly', collect())),
            'monthly' => UserChallengeResource::collection($groupedChallenges->get('monthly', collect())),
        ];

        return response()->json($response);
    }

    /**
     * Remove challenges for a specific user.
     *
     * @param Request $request
     * @return JsonResponse
     *
     * @OA\Delete(
     *      path="/api/user/challenges/delete/{user}",
     *      summary="Delete user challenges",
     *      tags={"User Challenges"},
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
     *      @OA\Response(
     *          response=200,
     *          description="Deleted",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="User challenges deleted successfully.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not Found"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal Server Error"
     *      )
     * )
     */
    public function destroy(Request $request): JsonResponse
    {
        $this->service->remove($request->user());
        return response()->json(['message' => 'User challenges deleted successfully.']);
    }
}
