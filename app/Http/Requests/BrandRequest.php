<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name'          => 'required|string|max:255',
            'status'        => 'required|in:active,inactive',
            'reference_url' => 'nullable|url',
        ];

        // Logo is required for store, optional for update
        if ($this->isMethod('post') || $this->isMethod('put') || $this->isMethod('patch')) {
            if ($this->hasFile('logo')) {
                $rules['logo'] = 'image|mimes:png,jpg,jpeg,webp|max:2048';
            } else {
                // For update, if no new logo is uploaded, keep the old one
                if ($this->isMethod('put') || $this->isMethod('patch')) {
                    $rules['logo'] = 'nullable';
                } else {
                    // For store, logo is required
                    $rules['logo'] = 'required|image|mimes:png,jpg,jpeg,webp|max:2048';
                }
            }
        }

        return $rules;
    }
}
