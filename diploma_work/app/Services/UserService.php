<?php

namespace App\Services;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserService
{
    private ImageService $service;

    public function __construct(ImageService $service)
    {
        $this->service = $service;
    }

    /**
     * @throws Exception
     */
    public function update(UserRequest $request, Authenticatable $user)
    {
        $validatedData = $request->validated();

        if (isset($validatedData['profile_picture'])) {
            if ($user->profile_picture && Storage::exists($user->profile_picture)) {
                Storage::delete($user->profile_picture);
            }

            $profilePicturePath = $this->service->store($request, 'profile_pictures');
            $validatedData['profile_picture'] = $profilePicturePath;
        }

        return DB::transaction(function () use ($validatedData, $user) {
            $user->update($validatedData);
            return $user;
        });
    }


    public function remove(User $user)
    {
        return DB::transaction(function () use ($user) {
            Storage::delete($user->profile_picture);
            $user->delete();
        });
    }


}
