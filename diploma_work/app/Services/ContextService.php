<?php

namespace App\Services;

use App\Interfaces\ChallengeInterface;
use App\Models\User;
use App\Models\UserChallenge;

class ContextService
{
    private ChallengeInterface $challenge;

    public function __construct(ChallengeInterface $challenge)
    {
        $this->challenge = $challenge;
    }

    public function checkChallenge(User $user, UserChallenge $userChallenge)
    {
        return $this->challenge->checkConditions($user, $userChallenge);
    }

}
