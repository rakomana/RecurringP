<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        return Subscription::query()->with(['user', 'plan'])->latest()->paginate();
    }

    public function store(Request $request)
    {
        $subscription = Subscription::create($this->validatedData($request));

        return response()->json($subscription->load(['user', 'plan']), 201);
    }

    public function show(Subscription $subscription)
    {
        return $subscription->load(['user', 'plan', 'invoices', 'usageRecords']);
    }

    public function update(Request $request, Subscription $subscription)
    {
        $subscription->update($this->validatedData($request));

        return $subscription->fresh()->load(['user', 'plan']);
    }

    public function destroy(Subscription $subscription)
    {
        $subscription->delete();

        return response()->noContent();
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'plan_id' => ['required', 'exists:plans,id'],
            'status' => ['required', 'string', 'in:trialing,active,past_due,cancelled,ended'],
            'trial_ends_at' => ['nullable', 'date'],
            'current_period_starts_at' => ['required', 'date'],
            'current_period_ends_at' => ['required', 'date', 'after_or_equal:current_period_starts_at'],
            'cancelled_at' => ['nullable', 'date'],
            'ended_at' => ['nullable', 'date'],
            'metadata' => ['nullable', 'array'],
        ]);
    }
}
