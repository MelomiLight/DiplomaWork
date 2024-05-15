<?php

namespace App\Services;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class UserService
{
    private $service;

    public function __construct(ImageService $service)
    {
        $this->service = $service;
    }

    /**
     * @throws Exception
     */
    public function update(UserRequest $request, User $user)
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

}
