<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * @throws ValidationException
     * @throws \Exception
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

// Check if user exists in the database
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw new \Exception('The provided credentials are incorrect.', 401);
        }

        return $user;
    }

    /**
     * @throws ValidationException
     * @throws \Exception
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

        try {
            DB::beginTransaction();
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $token = $user->createToken('API Token of ' . $user->name)->plainTextToken;

            DB::commit();
            return ['user' => $user, 'token' => $token];
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    /**
     * @throws ValidationException
     */
    public function createCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

        $user = User::where('email', $request->email)->first();

        $code = Str::random(6); // Generate a random code
        $user->update(['reset_code' => $code]);

        return $user;
    }

    /**
     * @throws ValidationException
     */
    public function authUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|confirmed',
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }
        $user = User::where('email', $request->email)->first();
        $user->update(['password' => Hash::make($request->password)]);
    }

    /**
     * @throws ValidationException
     * @throws \Exception
     */
    public function notAuthUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'reset_code' => 'required|string',
            'password' => 'required|string|confirmed',
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

        $user = User::where('email', $request->email)->where('reset_code', $request->reset_code)->first();
        if (!$user) {
            throw new \Exception('Invalid code', 422);
        }
        $user->update(['password' => Hash::make($request->password), 'reset_code' => null]);
    }
}
