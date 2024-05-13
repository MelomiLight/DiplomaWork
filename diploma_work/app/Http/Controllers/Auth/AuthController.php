<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangeRequest;
use App\Http\Requests\Auth\ForgotRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request, AuthService $authService): JsonResponse
    {
        $token = $authService->createUser($request);

        return response()->json(['token' => $token], 201);
    }


    public function login(LoginRequest $request, AuthService $authService): JsonResponse
    {
        $token = $authService->loginUser($request);

        return response()->json(['token' => $token], 201);
    }

    public function forgotPassword(ForgotRequest $request, AuthService $authService): JsonResponse
    {
        $user = $authService->forgotPassword($request);

        $authService->sendMailToUser($user);

        return response()->json(['message' => 'Password reset email sent successfully']);
    }

    public function changePassword(ChangeRequest $request, AuthService $authService): JsonResponse
    {
        $authService->changePassword($request);

        return response()->json(['message' => 'password successfully changed!']);
    }

    public function logout(): JsonResponse
    {
        try {
            // Ensure the user is authenticated
            Auth::user()->currentAccessToken()->delete();


            return response()->json(['message' => 'Successfully logged out']);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], $exception->getCode());
        }
    }
}
