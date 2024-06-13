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
            'distance_km' => ['required', 'numeric', 'min:0'],
            'start_time' => ['required', 'date'],
            'end_time' => ['required', 'date', 'after_or_equal:start_time'],
            'total_time' => ['required', 'date_format:H:i:s'],
            'average_speed' => ['required', 'numeric', 'min:0'],
            'max_speed' => ['required', 'numeric', 'min:0'],
            'calories_burned' => ['required', 'numeric', 'min:0'],
            'points' => ['required', 'integer', 'min:0'],
            'speeds' => ['required', 'array'],
            'speeds .*' => ['nullable', 'min:0'],
            'locations' => ['required', 'array'],
            'locations.*.latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'locations.*.longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ];
    }
}
