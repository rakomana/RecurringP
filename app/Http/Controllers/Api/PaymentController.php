<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function index()
    {
        return Payment::query()->with(['company', 'user', 'invoice'])->latest()->paginate();
    }

    public function store(PaymentRequest $request)
    {
        $payment = Payment::create($request->validated());

        return response()->json($payment->load(['company', 'user', 'invoice']), 201);
    }

    public function show(Payment $payment)
    {
        return $payment->load(['company', 'user', 'invoice', 'attempts']);
    }

    public function update(PaymentRequest $request, Payment $payment)
    {
        $payment->update($request->validated());

        return $payment->fresh()->load(['company', 'user', 'invoice']);
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();

        return response()->noContent();
    }

}
