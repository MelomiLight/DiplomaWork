<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthService
{

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Check if user exists in the database
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        return $user;
    }

    /**
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
            return response()->json(['errors' => $validator->errors()], 422);
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
        }catch (\Exception $exception){
            DB::rollBack();

            throw $exception;
        }
    }

    public function authUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|confirmed',
        ]);

        $user = User::where('email', $request->email)->first();
        $user->update(['password' => Hash::make($request->password)]);
    }

    public function notAuthUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'reset_code' => 'required|string',
            'password' => 'required|string|confirmed',
        ]);

        $user = User::where('email', $request->email)->where('reset_code', $request->reset_code)->first();
        if (!$user) {
            return response()->json(['error' => 'Invalid code'], 422);
        }
        $user->update(['password' => Hash::make($request->password), 'reset_code' => null]);
    }

    public function createCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        $code = Str::random(6); // Generate a random code
        $user->update(['reset_code' => $code]);

        return $user;
    }
}
