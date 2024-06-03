<?php

namespace App\Services;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    /**
     * @throws Exception
     */
    public function store($request, $path = 'images'): string
    {
        $image = $request->file('profile_picture');

        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->storeAs($path, $imageName);

        return $path . '/' . $imageName;
    }
}
