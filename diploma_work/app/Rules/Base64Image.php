<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;

class Base64Image implements ValidationRule
{
    protected $maxSize;
    protected $allowedMimes;

    public function __construct($maxSize = null, $allowedMimes = ['jpeg', 'png', 'jpg'])
    {
        $this->maxSize = $maxSize;
        $this->allowedMimes = $allowedMimes;
    }

    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        // Check if the value is a valid base64 string
        if (!preg_match('/^data:image\/(\w+);base64,/', $value, $type)) {
            $fail('The :attribute is not a valid base64 encoded image.');
            return;
        }

        $mimeType = strtolower($type[1]);

        // Check if the mime type is allowed
        if (!in_array($mimeType, $this->allowedMimes)) {
            $fail('The :attribute must be a file of type: ' . implode(', ', $this->allowedMimes) . '.');
            return;
        }

        // Decode the base64 string and check the file size if necessary
        $decodedImage = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $value));

        if ($decodedImage === false) {
            $fail('The :attribute is not a valid base64 encoded image.');
            return;
        }

        // Check the file size
        if ($this->maxSize && strlen($decodedImage) > $this->maxSize * 1024) {
            $fail('The :attribute may not be greater than ' . $this->maxSize . ' kilobytes.');
        }
    }
}

