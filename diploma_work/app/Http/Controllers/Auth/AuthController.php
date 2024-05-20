<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangeRequest;
use App\Http\Requests\Auth\ForgotRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\RunInformationResource;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    private $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    /**
     * @throws Exception
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $token = $this->service->createUser($request);

        return response()->json(['token' => $token], 201);
    }


    /**
     * @throws Exception
     */
    public function login(LoginRequest $request): UserResource|JsonResponse
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['error' => __('auth.failed')], 401);
        }

        // Retrieve the authenticated user
        $user = Auth::user();

        $token = $this->service->loginUser($user);
        return (new UserResource($user))->additional(['token' => $token]);
    }

    /**
     * @throws Exception
     */
    public function forgotPassword(ForgotRequest $request): JsonResponse
    {
        $user = $this->service->forgotPassword($request);

        $this->service->sendMailToUser($user);

        return response()->json(['message' => __('auth.sent')]);
    }

    /**
     * @throws Exception
     */
    public function changePassword(ChangeRequest $request): JsonResponse
    {
        $this->service->changePassword($request);

        return response()->json(['message' => __('auth.reset')]);
    }

    public function logout(): JsonResponse
    {
        try {
            // Ensure the user is authenticated
            Auth::user()->currentAccessToken()->delete();
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], $exception->getCode());
        }

        return response()->json(['message' => __('messages.logout_success')]);
    }
}
