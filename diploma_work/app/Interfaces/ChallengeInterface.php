<?php

namespace App\Interfaces;

use App\Models\User;
use App\Models\UserChallenge;

interface ChallengeInterface
{
    public function checkConditions(User $user, UserChallenge $userChallenge);
}
