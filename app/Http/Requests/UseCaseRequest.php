<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UseCaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'brand_id' => 'required|exists:brands,id',
            'name'     => 'required|string|max:255',
            'status'   => 'required|in:active,inactive',
        ];

        // For update, make some fields optional if needed
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            // Keep all fields required for now, adjust as needed
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'brand_id.required' => 'Please select a brand.',
            'brand_id.exists'   => 'The selected brand does not exist.',
            'name.required'     => 'Use case name is required.',
            'name.max'          => 'Use case name must not exceed 255 characters.',
            'status.required'   => 'Status is required.',
            'status.in'         => 'Status must be either active or inactive.',
        ];
    }
}
