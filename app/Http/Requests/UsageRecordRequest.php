<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsageRecordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id' => ['required', 'exists:companies,id'],
            'subscription_id' => ['required', 'exists:subscriptions,id'],
            'user_id' => ['required', 'exists:users,id'],
            'metric' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'integer', 'min:0'],
            'recorded_at' => ['required', 'date'],
            'metadata' => ['nullable', 'array'],
        ];
    }
}
