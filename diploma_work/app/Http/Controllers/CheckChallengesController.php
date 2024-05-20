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

    public function setChallenge(Request $request): JsonResponse
    {
        $user = $request->user();
        $status = false;
        $message = 'messages.challenge_check_error';
        $context = null;

        foreach ($user->userChallenges as $userChallenge) {
            if (!$userChallenge->challenge_status) {
                if ($userChallenge->challenge->challenge_type == 'distanceChallenge') {
                    $context = new ContextService(new DistanceChallengeController($this->service));
                }

                if ($context) {
                    $status = $context->checkChallenge($user, $userChallenge);
                    $message = 'messages.challenge_check_success';
                }
            }
        }

        return response()->json([
            'status' => $status,
            'message' => __($message)
        ]);
    }


    public function store(ChallengeRequest $request): ChallengeResource
    {
        $challenge = $this->service->create($request);

        return new ChallengeResource($challenge);
    }

    public function index(): AnonymousResourceCollection
    {
        $challenges = $this->repository->all();
        return ChallengeResource::collection($challenges);
    }

    public function destroy(Challenge $challenge)
    {
        return $this->service->remove($challenge);
    }
}
