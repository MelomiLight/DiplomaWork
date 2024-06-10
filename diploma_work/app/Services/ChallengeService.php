<?php

namespace App\Services;

use App\Http\Requests\ChallengeRequest;
use App\Models\Challenge;
use App\Models\User;
use App\Models\UserChallenge;
use App\Models\UserPoint;
use Illuminate\Support\Facades\DB;

class ChallengeService
{
    public function create(ChallengeRequest $request)
    {
        return DB::transaction(function () use ($request) {
            return Challenge::create($request->validated());
        });
    }

    public function remove(Challenge $challenge)
    {
        return DB::transaction(function () use ($challenge) {
            $challenge->delete();
        });
    }
}

