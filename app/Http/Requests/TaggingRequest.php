<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaggingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'source'  => 'required|string|max:255',
            'status'  => 'required|in:online,offline',
            'ref_url' => 'nullable|url|max:255',
        ];
    }

    public function messages()
    {
        return [
            'source.required'  => 'Source is required',
            'status.required'  => 'Please select status',
            'ref_url.url'      => 'Reference URL must be valid',
        ];
    }
}
