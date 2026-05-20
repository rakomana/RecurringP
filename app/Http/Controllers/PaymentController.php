<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        return Payment::query()->with(['user', 'invoice'])->latest()->paginate();
    }

    public function store(Request $request)
    {
        $payment = Payment::create($this->validatedData($request));

        return response()->json($payment->load(['user', 'invoice']), 201);
    }

    public function show(Payment $payment)
    {
        return $payment->load(['user', 'invoice', 'attempts']);
    }

    public function update(Request $request, Payment $payment)
    {
        $payment->update($this->validatedData($request));

        return $payment->fresh()->load(['user', 'invoice']);
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();

        return response()->noContent();
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'invoice_id' => ['required', 'exists:invoices,id'],
            'user_id' => ['required', 'exists:users,id'],
            'provider' => ['required', 'string', 'max:255'],
            'provider_payment_id' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:pending,succeeded,failed,refunded'],
            'amount' => ['required', 'integer', 'min:0'],
            'currency' => ['required', 'string', 'size:3'],
            'paid_at' => ['nullable', 'date'],
            'metadata' => ['nullable', 'array'],
        ]);
    }
}
