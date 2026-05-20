<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id' => ['required', 'exists:companies,id'],
            'user_id' => ['required', 'exists:users,id'],
            'plan_id' => ['required', 'exists:plans,id'],
            'status' => ['required', 'string', 'in:trialing,active,past_due,cancelled,ended'],
            'trial_ends_at' => ['nullable', 'date'],
            'current_period_starts_at' => ['required', 'date'],
            'current_period_ends_at' => ['required', 'date', 'after_or_equal:current_period_starts_at'],
            'cancelled_at' => ['nullable', 'date'],
            'ended_at' => ['nullable', 'date'],
            'metadata' => ['nullable', 'array'],
        ];
    }
}
