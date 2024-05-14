<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RunningSessionRequest extends FormRequest
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
            'user_id' => 'nullable|exists:users,id',
            'distance_km' => 'nullable|numeric|min:0',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
            'total_time' => 'nullable|date_format:H:i:s',
            'average_speed' => 'nullable|numeric|min:0',
            'max_speed' => 'nullable|numeric|min:0',
            'calories_burned' => 'nullable|numeric|min:0',
            'points' => 'nullable|integer|min:0',
        ];
    }
}
