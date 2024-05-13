<?php

namespace App\Services;

use App\Http\Requests\Auth\ChangeRequest;
use App\Http\Requests\Auth\ForgotRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Mail\SendForgotPassword;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthService
{
    /**
     * @throws Exception
     */
    public function createUser(RegisterRequest $request)
    {
        try {
            $request->password = Hash::make($request->password);
            $user = User::create($request->all());
        } catch (\Exception) {
            throw new Exception('Could not create user', 500);
        }

        return $user->createToken('API token of ' . $user->name)->plainTextToken;
    }

    /**
     * @throws Exception
     */
    public function loginUser(Authenticatable $user)
    {
        try {
            $token = $user->createToken('API token of ' . $user->name)->plainTextToken;
        } catch (\Exception $e) {
            throw new Exception('Could not create token. ' . $e->getMessage(), 500);
        }

        return $token;
    }

    /**
     * @throws Exception
     */
    public function forgotPassword(ForgotRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->first();
            $code = Str::random(6); // Generate a random code
            $user->update(['reset_code' => $code]);

            return $user;
        } catch (\Exception) {
            throw new Exception('Could not create reset code', 500);
        }

    }

    /**
     * @throws Exception
     */
    public function sendMailToUser(User $user): void
    {
        try {
            Mail::to($user->email)->send(new SendForgotPassword($user->reset_code));
        } catch (\Exception) {
            throw new Exception('Could not send mail', 500);
        }
    }

    /**
     * @throws Exception
     */
    public function changePassword(ChangeRequest $request): void
    {
        try {
            $request->password = Hash::make($request->password);

            $user = User::where('email', $request->email)->first();
            $user->update([
                'password' => $request->password,
                'reset_code' => null,
            ]);
        } catch (\Exception) {
            throw new Exception('Could not change password', 500);
        }
    }
}

