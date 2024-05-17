<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChallengeRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'due_type' => 'required|string|in:daily,weekly,monthly',
            'challenge_type' => 'required|string|in:distanceChallenge',
            'is_active' => 'boolean',
            'points' => 'required|integer',
            'distance_km' => 'nullable|numeric|min:0',
        ];
    }
}
