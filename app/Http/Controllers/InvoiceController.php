<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InvoiceController extends Controller
{
    public function index()
    {
        return Invoice::query()->with(['user', 'subscription'])->latest()->paginate();
    }

    public function store(Request $request)
    {
        $invoice = Invoice::create($this->validatedData($request));

        return response()->json($invoice->load(['user', 'subscription']), 201);
    }

    public function show(Invoice $invoice)
    {
        return $invoice->load(['user', 'subscription', 'payments', 'paymentAttempts']);
    }

    public function update(Request $request, Invoice $invoice)
    {
        $invoice->update($this->validatedData($request, $invoice));

        return $invoice->fresh()->load(['user', 'subscription']);
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return response()->noContent();
    }

    private function validatedData(Request $request, ?Invoice $invoice = null): array
    {
        return $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'subscription_id' => ['nullable', 'exists:subscriptions,id'],
            'number' => ['required', 'string', 'max:255', Rule::unique('invoices', 'number')->ignore($invoice)],
            'status' => ['required', 'string', 'in:draft,open,paid,void,uncollectible'],
            'subtotal' => ['required', 'integer', 'min:0'],
            'tax' => ['nullable', 'integer', 'min:0'],
            'total' => ['required', 'integer', 'min:0'],
            'currency' => ['required', 'string', 'size:3'],
            'issued_at' => ['nullable', 'date'],
            'due_at' => ['nullable', 'date'],
            'paid_at' => ['nullable', 'date'],
            'metadata' => ['nullable', 'array'],
        ]);
    }
}
