<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('companies', 'slug')->ignore($this->route('company'))],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'default_currency' => ['required', 'string', 'size:3'],
            'timezone' => ['required', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'billing_settings' => ['nullable', 'array'],
            'metadata' => ['nullable', 'array'],
            'user_ids' => ['nullable', 'array'],
            'user_ids.*' => ['integer', 'exists:users,id'],
        ];
    }
}
