<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InvoiceRequest extends FormRequest
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
            'subscription_id' => ['nullable', 'exists:subscriptions,id'],
            'number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('invoices', 'number')
                    ->where('company_id', $this->input('company_id'))
                    ->ignore($this->route('invoice')),
            ],
            'status' => ['required', 'string', 'in:draft,open,paid,void,uncollectible'],
            'subtotal' => ['required', 'integer', 'min:0'],
            'tax' => ['nullable', 'integer', 'min:0'],
            'total' => ['required', 'integer', 'min:0'],
            'currency' => ['required', 'string', 'size:3'],
            'issued_at' => ['nullable', 'date'],
            'due_at' => ['nullable', 'date'],
            'paid_at' => ['nullable', 'date'],
            'metadata' => ['nullable', 'array'],
        ];
    }
}
