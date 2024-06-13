<?php

namespace App\Http\Controllers;

use App\Http\Requests\RunningSessionRequest;
use App\Http\Resources\RunningSessionResource;
use App\Models\RunningSession;
use App\Repositories\RunningSessionRepository;
use App\Services\RunningSessionService;
use Illuminate\Http\JsonResponse;
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

    /**
     * Get a list of running sessions for the authenticated user.
     *
     * @param RunningSessionRequest $request
     * @return AnonymousResourceCollection
     *
     * @OA\Get(
     *      path="/api/run/session/index",
     *      summary="Get running sessions",
     *      tags={"Running Sessions"},
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
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/RunningSessionResource")
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal Server Error"
     *      )
     * )
     */
    public function index(RunningSessionRequest $request): AnonymousResourceCollection
    {
        $runningSessions = $this->repository->all($request->user());
        return RunningSessionResource::collection($runningSessions);
    }


    /**
     * Store a new running session.
     *
     * @param RunningSessionRequest $request
     * @return RunningSessionResource
     *
     * @OA\Post(
     *      path="/api/run/session/store",
     *      summary="Store a new running session",
     *      tags={"Running Sessions"},
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
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/RunningSessionResource")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Created",
     *          @OA\JsonContent(ref="#/components/schemas/RunningSessionResource")
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal Server Error"
     *      )
     * )
     */
    public function store(RunningSessionRequest $request): RunningSessionResource
    {
        $runningSession = $this->service->create($request);

        return new RunningSessionResource($runningSession);
    }

    /**
     * Delete a running session.
     *
     * @param RunningSession $runningSession
     * @return JsonResponse
     *
     * @OA\Delete(
     *      path="/api/run/session/delete/{runningSession}",
     *      summary="Delete a running session",
     *      tags={"Running Sessions"},
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
     *          name="runningSession",
     *          in="path",
     *          required=true,
     *          description="ID of the running session to delete",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Deleted",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Running session deleted successfully.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Running session not found"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal Server Error"
     *      )
     * )
     */
    public function destroy(RunningSession $runningSession): JsonResponse
    {
        $this->service->remove($runningSession);
        return response()->json(['message' => __('messages.delete_success', ['attribute' => 'Running session'])]);
    }
}

