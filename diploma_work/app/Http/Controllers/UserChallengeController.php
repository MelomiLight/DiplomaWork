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

    public function show(User $user): AnonymousResourceCollection
    {
        $userChallenges = $user->userChallenges;
        return UserChallengeResource::collection($userChallenges);
    }

    public function destroy(User $user)
    {
        return $this->service->remove($user);
    }
}
