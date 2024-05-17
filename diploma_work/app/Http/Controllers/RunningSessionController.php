<?php

namespace App\Http\Controllers;

use App\Http\Requests\RunningSessionRequest;
use App\Http\Resources\RunningSessionResource;
use App\Models\RunningSession;
use App\Repositories\RunningSessionRepository;
use App\Services\RunningSessionService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RunningSessionController extends Controller
{
    protected RunningSessionService $service;
    protected RunningSessionRepository $repository;


    public function __construct(RunningSessionRepository $repository, RunningSessionService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    public function index(RunningSessionRequest $request): AnonymousResourceCollection
    {
        $runningSessions = $this->repository->all($request->user());
        return RunningSessionResource::collection($runningSessions);
    }


    public function store(RunningSessionRequest $request): RunningSessionResource
    {
        $runningSession = $this->service->create($request);
        $this->service->addUserPoints($runningSession);
        $this->service->addRunInformation($runningSession);
        return new RunningSessionResource($runningSession);
    }

    public function destroy(RunningSession $runningSession)
    {
        return $this->service->remove($runningSession);
    }

}
