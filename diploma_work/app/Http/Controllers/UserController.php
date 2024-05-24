<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\RunInformationResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\UserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    private UserService $service;
    private UserRepository $repository;

    public function __construct(UserRepository $repository, UserService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    /**
     * Update the authenticated user's information.
     *
     * @param UserRequest $request
     * @return UserResource
     *
     * @throws Exception
     * @OA\Patch(
     *      path="/api/user/update",
     *      summary="Update user information",
     *      tags={"User"},
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
     *          @OA\JsonContent(
     *              @OA\Property(property="name", type="string", example="John Doe"),
     *              @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *              @OA\Property(property="profile_picture", type="string", example="base64 encoded string"),
     *              @OA\Property(property="weight_kg", type="number", format="float", example=70.5),
     *              @OA\Property(property="height_cm", type="number", format="float", example=175)
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\JsonContent(ref="#/components/schemas/UserResource")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal Server Error"
     *      )
     * )
     */
    public function update(UserRequest $request): UserResource
    {
        $user = Auth::user();
        $this->service->update($request, $user);
        return new UserResource($user);
    }

    /**
     * Display the specified user information.
     *
     * @param User $user
     * @return UserResource
     *
     * @OA\Get(
     *      path="/api/user/show/{user}",
     *      summary="Get user information",
     *      tags={"User"},
     *      @OA\Parameter(
     *          name="user",
     *          in="path",
     *          required=true,
     *          description="ID of the user",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="User information",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", ref="#/components/schemas/UserResource"),
     *              @OA\Property(property="run_info", ref="#/components/schemas/RunInformationResource")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="User not found"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal Server Error"
     *      )
     * )
     */
    public function show(User $user): UserResource
    {
        $run_info = new RunInformationResource($user->runInformation);
        return (new UserResource($user))->additional(['run_info' => $run_info]);
    }
    /**
     * Display a listing of the users.
     *
     * @return AnonymousResourceCollection
     *
     * @OA\Get(
     *      path="/api/user/index",
     *      summary="Get list of users",
     *      tags={"User"},
     *      @OA\Response(
     *          response=200,
     *          description="List of users",
     *          @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/UserResource"))
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal Server Error"
     *      )
     * )
     */
    public function index(): AnonymousResourceCollection
    {
        $users = $this->repository->all();
        return UserResource::collection($users);
    }

    /**
     * Remove the specified user from storage.
     *
     * @param User $user
     * @return JsonResponse
     *
     * @OA\Delete(
     *      path="/api/user/delete/{user}",
     *      summary="Delete user",
     *      tags={"User"},
     *      @OA\Parameter(
     *          name="user",
     *          in="path",
     *          required=true,
     *          description="ID of the user to delete",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="User deleted successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="User deleted successfully.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="User not found"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal Server Error"
     *      )
     * )
     */
    public function destroy(User $user): JsonResponse
    {
        $this->service->remove($user);
        return response()->json(['message' => __('messages.delete_success', ['attribute' => 'User'])]);
    }
}
