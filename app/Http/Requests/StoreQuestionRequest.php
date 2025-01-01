<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'question' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'nullable|date|before_or_equal:end_time',
            'end_time' => 'nullable|date|after_or_equal:start_time',
            'time' => 'nullable|date_format:H:i',
            'status' => 'required|in:active,inactive,archived',
            'is_public' => 'required|boolean',
        ];
    }
}
