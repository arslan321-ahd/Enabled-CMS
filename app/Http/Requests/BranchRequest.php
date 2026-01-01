<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BranchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:255',
            'email'    => 'email|max:255',
            'phone'    => 'nullable|string|max:20',
            'role'     => 'required|in:admin,user',
            'status'   => 'required|boolean',
            'password' => 'nullable|string',
        ];
    }
}
