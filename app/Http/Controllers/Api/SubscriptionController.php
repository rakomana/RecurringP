<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionRequest;
use App\Models\Subscription;

class SubscriptionController extends Controller
{
    public function index()
    {
        return Subscription::query()->with(['company', 'user', 'plan'])->latest()->paginate();
    }

    public function store(SubscriptionRequest $request)
    {
        $subscription = Subscription::create($request->validated());

        return response()->json($subscription->load(['company', 'user', 'plan']), 201);
    }

    public function show(Subscription $subscription)
    {
        return $subscription->load(['company', 'user', 'plan', 'invoices', 'usageRecords']);
    }

    public function update(SubscriptionRequest $request, Subscription $subscription)
    {
        $subscription->update($request->validated());

        return $subscription->fresh()->load(['company', 'user', 'plan']);
    }

    public function destroy(Subscription $subscription)
    {
        $subscription->delete();

        return response()->noContent();
    }

}
