<?php

namespace App\Http\Controllers\Auth;

use App\Mail\PasswordResetMail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class AuthController extends BaseController
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function login(Request $request): JsonResponse
    {
        $user = $this->service->login($request);

        if (Auth::attempt(['email' => $user->email, 'password' => $user->password])) {
            $token = $user->createToken('API Token')->plainTextToken;

            return response()->json(['user' => $user, 'token' => $token], 200);
        } else {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {

        $data = $this->service->register($request);

        return response()->json(['user' => $data['user'], 'token' => $data['token']], 201);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function forgotPassword(Request $request): JsonResponse
    {

        $user = $this->service->createCode($request);

        // Send the password reset email
        Mail::to($user->email)->send(new PasswordResetMail($user->code));

        return response()->json(['message' => 'Password reset email sent successfully']);
    }

    public function resetPassword(Request $request)
    {

        if (!Auth::check()) {
            $this->service->notAuthUser($request);
        } else {
            $this->service->authUser($request);
        }


        return response()->json(['message' => 'Password reset successfully']);
    }
}
