<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WebhookEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id' => ['required', 'exists:companies,id'],
            'provider' => ['required', 'string', 'max:255'],
            'event_id' => [
                'required',
                'string',
                'max:255',
                Rule::unique('webhook_events', 'event_id')
                    ->where('company_id', $this->input('company_id'))
                    ->ignore($this->route('webhookEvent')),
            ],
            'event_type' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:pending,processed,failed'],
            'payload' => ['required', 'array'],
            'processed_at' => ['nullable', 'date'],
        ];
    }
}
