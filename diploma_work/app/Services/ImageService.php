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
    public function store($imageData, $path = 'images'): string
    {
        try {
            $imageName = $this->saveBase64Image($imageData, $path);
        } catch (Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $imageName;
    }

    private function saveBase64Image($base64Image, $path): string
    {
        // Extract the file extension from the base64 string
        preg_match('/data:image\/(.*?);base64,/', $base64Image, $matches);
        $extension = $matches[1];

        // Remove the base64 header
        $image = preg_replace('/^data:image\/(.*?);base64,/', '', $base64Image);
        $image = str_replace(' ', '+', $image);

        // Generate a unique filename with the correct extension
        $imageName = $path . '/' . uniqid() . '.' . $extension;

        // Save the image to the storage
        Storage::disk('public')->put($imageName, base64_decode($image));

        return $imageName;
    }

    /**
     * @throws Exception
     */
    public static function getBase64Image($path): string
    {
        if ($path) {
            if (Storage::disk('public')->exists($path)) {
                $file = Storage::disk('public')->get($path);
                $type = Storage::disk('public')->mimeType($path);
                // Encode the image back to base64
                return 'data:' . $type . ';base64,' . base64_encode($file);
            }
        }
        return '';

    }
}
