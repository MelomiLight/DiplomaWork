<?php


namespace App\Http\Requests;

use App\Rules\Base64Image;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'string', 'email', 'max:255', 'unique:users', 'bail'],
            'profile_picture' => ['nullable', 'sometimes', new Base64Image(2048, ['jpeg', 'png', 'jpg'])],
            'weight_kg' => ['sometimes', 'numeric', 'between:0,500'],
            'height_cm' => ['sometimes', 'numeric', 'between:0,300'],
        ];
    }
}
