<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id' => ['required', 'exists:companies,id'],
            'invoice_id' => ['required', 'exists:invoices,id'],
            'user_id' => ['required', 'exists:users,id'],
            'provider' => ['required', 'string', 'max:255'],
            'provider_payment_id' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:pending,succeeded,failed,refunded'],
            'amount' => ['required', 'integer', 'min:0'],
            'currency' => ['required', 'string', 'size:3'],
            'paid_at' => ['nullable', 'date'],
            'metadata' => ['nullable', 'array'],
        ];
    }
}
