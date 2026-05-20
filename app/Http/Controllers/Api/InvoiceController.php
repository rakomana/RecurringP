<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\InvoiceRequest;
use App\Models\Invoice;

class InvoiceController extends Controller
{
    public function index()
    {
        return Invoice::query()->with(['company', 'user', 'subscription'])->latest()->paginate();
    }

    public function store(InvoiceRequest $request)
    {
        $invoice = Invoice::create($request->validated());

        return response()->json($invoice->load(['company', 'user', 'subscription']), 201);
    }

    public function show(Invoice $invoice)
    {
        return $invoice->load(['company', 'user', 'subscription', 'payments', 'paymentAttempts']);
    }

    public function update(InvoiceRequest $request, Invoice $invoice)
    {
        $invoice->update($request->validated());

        return $invoice->fresh()->load(['company', 'user', 'subscription']);
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return response()->noContent();
    }

}
