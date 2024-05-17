<?php

namespace App\Http\Controllers;

use App\Interfaces\ChallengeInterface;
use App\Models\User;
use App\Models\UserChallenge;
use App\Services\ChallengeService;

class DistanceChallengeController extends Controller implements ChallengeInterface
{
    private ChallengeService $service;
    public function __construct(ChallengeService $service)
    {
        $this->service = $service;
    }

    public function checkConditions(User $user, UserChallenge $userChallenge): bool
    {
        return match ($userChallenge->challenge->due_type) {
            'daily' => $this->service->checkDaily($user, $userChallenge),
            'weekly' => $this->service->checkWeekly($user, $userChallenge),
            'monthly' => $this->service->checkMonthly($user, $userChallenge),
            default => false,
        };
    }
}
