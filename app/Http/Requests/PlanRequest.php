<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id' => ['required', 'exists:companies,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('plans', 'slug')
                    ->where('company_id', $this->input('company_id'))
                    ->ignore($this->route('plan')),
            ],
            'description' => ['nullable', 'string'],
            'amount' => ['required', 'integer', 'min:0'],
            'currency' => ['required', 'string', 'size:3'],
            'billing_interval' => ['required', 'string', 'in:day,week,month,year'],
            'trial_days' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'metadata' => ['nullable', 'array'],
        ];
    }
}
