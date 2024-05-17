<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\RunInformationResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\UserService;
use Exception;
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
     * @throws Exception
     */
    public function update(UserRequest $request): UserResource
    {
        $user = Auth::user();
        $this->service->update($request, $user);
        return new UserResource($user);
    }

    public function show(User $user): UserResource
    {
        $run_info = new RunInformationResource($user->runInformation);
        return (new UserResource($user))->additional(['run_info' => $run_info]);
    }

    public function index(): AnonymousResourceCollection
    {
        $users = $this->repository->all();
        return UserResource::collection($users);
    }

    public function destroy(User $user)
    {
        return $this->service->remove($user);
    }

}
