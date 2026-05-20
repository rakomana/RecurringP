<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentAttemptRequest;
use App\Models\PaymentAttempt;

class PaymentAttemptController extends Controller
{
    public function index()
    {
        return PaymentAttempt::query()->with(['company', 'invoice', 'payment'])->latest()->paginate();
    }

    public function store(PaymentAttemptRequest $request)
    {
        $paymentAttempt = PaymentAttempt::create($request->validated());

        return response()->json($paymentAttempt->load(['company', 'invoice', 'payment']), 201);
    }

    public function show(PaymentAttempt $paymentAttempt)
    {
        return $paymentAttempt->load(['company', 'invoice', 'payment']);
    }

    public function update(PaymentAttemptRequest $request, PaymentAttempt $paymentAttempt)
    {
        $paymentAttempt->update($request->validated());

        return $paymentAttempt->fresh()->load(['company', 'invoice', 'payment']);
    }

    public function destroy(PaymentAttempt $paymentAttempt)
    {
        $paymentAttempt->delete();

        return response()->noContent();
    }

}
