<?php

namespace App\Http\Controllers;

use App\Models\PaymentAttempt;
use Illuminate\Http\Request;

class PaymentAttemptController extends Controller
{
    public function index()
    {
        return PaymentAttempt::query()->with(['invoice', 'payment'])->latest()->paginate();
    }

    public function store(Request $request)
    {
        $paymentAttempt = PaymentAttempt::create($this->validatedData($request));

        return response()->json($paymentAttempt->load(['invoice', 'payment']), 201);
    }

    public function show(PaymentAttempt $paymentAttempt)
    {
        return $paymentAttempt->load(['invoice', 'payment']);
    }

    public function update(Request $request, PaymentAttempt $paymentAttempt)
    {
        $paymentAttempt->update($this->validatedData($request));

        return $paymentAttempt->fresh()->load(['invoice', 'payment']);
    }

    public function destroy(PaymentAttempt $paymentAttempt)
    {
        $paymentAttempt->delete();

        return response()->noContent();
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
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
        ]);
    }
}
