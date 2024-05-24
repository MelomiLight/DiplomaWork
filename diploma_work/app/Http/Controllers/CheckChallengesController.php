<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChallengeRequest;
use App\Http\Resources\ChallengeResource;
use App\Models\Challenge;
use App\Repositories\ChallengeRepository;
use App\Services\ChallengeService;
use App\Services\ContextService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CheckChallengesController extends Controller
{
    private ChallengeService $service;
    private ChallengeRepository $repository;

    public function __construct(ChallengeService $challengeService, ChallengeRepository $repository)
    {
        $this->service = $challengeService;
        $this->repository = $repository;
    }

    /**
     * Check and set challenges for the authenticated user.
     *
     * @param Request $request
     * @return JsonResponse
     *
     * @OA\Get(
     *      path="/api/challenges/check",
     *      summary="Check and set challenges",
     *      tags={"Challenges"},
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
     *              @OA\Property(property="status", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="You have completed challenges!")
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal Server Error"
     *      )
     * )
     */
    public function setChallenge(Request $request): JsonResponse
    {
        $user = $request->user();
        $status = false;
        $context = null;

        foreach ($user->userChallenges as $userChallenge) {
            if (!$userChallenge->challenge_status) {
                if ($userChallenge->challenge->challenge_type == 'distanceChallenge') {
                    $context = new ContextService(new DistanceChallengeController($this->service));
                }

                if ($context) {
                    $status = $context->checkChallenge($user, $userChallenge);
                }
            }
        }
        $message = $status ? 'messages.challenge_check_success' : 'messages.challenge_check_error';

        return response()->json([
            'status' => $status,
            'message' => __($message)
        ]);
    }

    /**
     * Store a new challenge.
     *
     * @param ChallengeRequest $request
     * @return ChallengeResource
     *
     * @OA\Post(
     *      path="/api/challenges/store",
     *      summary="Store a new challenge",
     *      tags={"Challenges"},
     *      @OA\Parameter(
     *          name="Accept",
     *          in="header",
     *          required=true,
     *          example="application/json"
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/ChallengeResource")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Created",
     *          @OA\JsonContent(ref="#/components/schemas/ChallengeResource")
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal Server Error"
     *      )
     * )
     */
    public function store(ChallengeRequest $request): ChallengeResource
    {
        $challenge = $this->service->create($request);

        return new ChallengeResource($challenge);
    }

    /**
     * Get a list of challenges.
     *
     * @return AnonymousResourceCollection
     *
     * @OA\Get(
     *      path="/api/challenges/index",
     *      summary="Get a list of challenges",
     *      tags={"Challenges"},
     *      @OA\Parameter(
     *          name="Accept",
     *          in="header",
     *          required=true,
     *          example="application/json"
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/ChallengeResource")
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal Server Error"
     *      )
     * )
     */
    public function index(): AnonymousResourceCollection
    {
        $challenges = $this->repository->all();
        return ChallengeResource::collection($challenges);
    }

    /**
     * Delete a challenge.
     *
     * @param Challenge $challenge
     * @return JsonResponse
     *
     * @OA\Delete(
     *      path="/api/challenges/delete/{challenge}",
     *      summary="Delete a challenge",
     *      tags={"Challenges"},
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
     *          name="challenge",
     *          in="path",
     *          required=true,
     *          description="ID of the challenge to delete",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Deleted",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Challenge deleted successfully.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Challenge not found"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal Server Error"
     *      )
     * )
     */
    public function destroy(Challenge $challenge): JsonResponse
    {
        $this->service->remove($challenge);
        return response()->json(['message' => __('messages.delete_success', ['attribute' => 'Challenge'])]);
    }
}
