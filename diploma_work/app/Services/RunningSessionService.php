<?php

namespace App\Services;

use App\Http\Requests\RunningSessionRequest;
use App\Models\RunningSession;
use Illuminate\Support\Facades\DB;

class RunningSessionService
{
    public function create(RunningSessionRequest $request)
    {
        return DB::transaction(function () use ($request) {
            return RunningSession::create($request->validated());
        });
    }

}
