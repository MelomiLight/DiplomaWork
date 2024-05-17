<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserChallengeRequest;
use App\Http\Resources\ChallengeResource;
use App\Http\Resources\UserChallengeResource;
use App\Models\User;
use App\Repositories\UserChallengeRepository;
use App\Services\UserChallengeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserChallengeController extends Controller
{
    private UserChallengeService $service;

    public function __construct(UserChallengeService $challengeService)
    {
        $this->service = $challengeService;
    }

    public function store(UserChallengeRequest $request): JsonResponse
    {
        $userChallenge = $this->service->create($request);

        return response()->json($userChallenge);
    }

    public function show(User $user): JsonResponse
    {
        $userChallenges = $user->userChallenges;

        $groupedChallenges = $userChallenges->groupBy(function($item) {
            return $item->challenge->due_type;
        });

        $response = [
            'daily' => UserChallengeResource::collection($groupedChallenges->get('daily', collect())),
            'weekly' => UserChallengeResource::collection($groupedChallenges->get('weekly', collect())),
            'monthly' => UserChallengeResource::collection($groupedChallenges->get('monthly', collect())),
        ];

        return response()->json($response);
    }

    public function destroy(User $user): void
    {
        $this->service->remove($user);
    }
}
