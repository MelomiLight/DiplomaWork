<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangeRequest;
use App\Http\Requests\Auth\ForgotRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

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
            return response()->json(['error' => 'The provided credentials are incorrect.'], 401);
        }

        // Retrieve the authenticated user
        $user = Auth::user();

        $token = $this->service->loginUser($user);

        return new UserResource($user, $token);
    }

    /**
     * @throws Exception
     */
    public function forgotPassword(ForgotRequest $request): JsonResponse
    {
        $user = $this->service->forgotPassword($request);

        $this->service->sendMailToUser($user);

        return response()->json(['message' => 'Password reset email sent successfully']);
    }

    /**
     * @throws Exception
     */
    public function changePassword(ChangeRequest $request): JsonResponse
    {
        $this->service->changePassword($request);

        return response()->json(['message' => 'password successfully changed!']);
    }

    public function logout(): JsonResponse
    {
        try {
            // Ensure the user is authenticated
            Auth::user()->currentAccessToken()->delete();
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], $exception->getCode());
        }


        return response()->json(['message' => 'Successfully logged out']);
    }
}
