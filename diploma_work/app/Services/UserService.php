<?php

namespace App\Services;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\DB;

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
        if($validatedData['profile_picture']??null){
            // Store the base64 image and get the path
            $profilePicturePath = $this->service->store($validatedData['profile_picture'], 'profile_pictures');

            $validatedData['profile_picture'] = $profilePicturePath;
        }
        // Update the user inside a transaction
        return DB::transaction(function () use ($validatedData, $user) {
            return $user->update($validatedData);
        });
    }

    public function remove(User $user)
    {
        return DB::transaction(function () use ($user) {
            $user->delete();
        });
    }


}
