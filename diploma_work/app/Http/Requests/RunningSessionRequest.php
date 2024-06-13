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
            'distance_km' => ['nullable', 'numeric', 'min:0'],
            'start_time' => ['nullable', 'date'],
            'end_time' => ['nullable', 'date', 'after_or_equal:start_time'],
            'total_time' => ['nullable', 'date_format:H:i:s'],
            'average_speed' => ['nullable', 'numeric', 'min:0'],
            'max_speed' => ['nullable', 'numeric', 'min:0'],
            'calories_burned' => ['nullable', 'numeric', 'min:0'],
            'points' => ['nullable', 'integer', 'min:0'],
            'speeds' => ['required', 'array'],
            'speeds .*' => ['numeric', 'min:0'],
            'locations' => ['required', 'array'],
            'locations.*.latitude' => ['required', 'numeric', 'between:-90,90'],
            'locations.*.longitude' => ['required', 'numeric', 'between:-180,180'],
        ];
    }
}
