<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetMail;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected AuthService $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    public function login(Request $request): JsonResponse
    {
        try {
            $user = $this->service->login($request);
            $token = $user->createToken('API Token')->plainTextToken;

            return response()->json(['user' => $user, 'token' => $token], 200);
        } catch (ValidationException $exception) {
            return response()->json(['errors' => $exception->errors()], 422);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], $exception->getCode());
        }
    }

    public function register(Request $request): JsonResponse
    {
        try {
            $data = $this->service->register($request);
            return response()->json(['token' => $data['token']], 201);
        } catch (ValidationException $exception) {
            return $exception->response;
        } catch (\Exception $exception) {
            return response()->json(['error' => 'An error occurred while creating the user: ' . $exception->getMessage()], 500);
        }
    }


    public function logout(Request $request): JsonResponse
    {
        try {
            // Ensure the user is authenticated
            Auth::user()->currentAccessToken()->delete();


            return response()->json(['message' => 'Successfully logged out']);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], $exception->getCode());
        }
    }


    public function forgotPassword(Request $request): JsonResponse
    {
        try {
            $user = $this->service->createCode($request);

            // Send the password reset email
            Mail::to($user->email)->send(new PasswordResetMail($user->reset_code));

            return response()->json(['message' => 'Password reset email sent successfully']);
        } catch (\Exception $exception) {
            // Log the exception for debugging purposes


            // Return a generic error message with status code 500
            return response()->json(['error' => 'An error occurred while sending the password reset email. Please try again later.'], 500);
        }
    }


    public function resetPassword(Request $request): JsonResponse
    {
        try {
            $this->service->notAuthUser($request);

            return response()->json(['message' => 'Password reset successfully']);
        } catch (ValidationException $exception) {
            return response()->json(['errors' => $exception->errors()], 422);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], $exception->getCode());
        }
    }

    public function resetAuthPassword(Request $request): JsonResponse
    {
        try {
            // Perform password reset logic
            $this->service->authUser($request);

            return response()->json(['message' => 'Password reset successfully']);
        } catch (ValidationException $exception) {
            return response()->json(['errors' => $exception->errors()], 422);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], $exception->getCode());
        }
    }

}
