<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentAttemptRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id' => ['required', 'exists:companies,id'],
            'payment_id' => ['nullable', 'exists:payments,id'],
            'invoice_id' => ['required', 'exists:invoices,id'],
            'provider' => ['required', 'string', 'max:255'],
            'provider_attempt_id' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:pending,succeeded,failed'],
            'amount' => ['required', 'integer', 'min:0'],
            'currency' => ['required', 'string', 'size:3'],
            'failure_reason' => ['nullable', 'string'],
            'attempted_at' => ['nullable', 'date'],
            'response_payload' => ['nullable', 'array'],
        ];
    }
}
