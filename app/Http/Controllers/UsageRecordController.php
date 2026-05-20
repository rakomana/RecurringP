<?php

namespace App\Http\Controllers;

use App\Models\UsageRecord;
use Illuminate\Http\Request;

class UsageRecordController extends Controller
{
    public function index()
    {
        return UsageRecord::query()->with(['user', 'subscription'])->latest()->paginate();
    }

    public function store(Request $request)
    {
        $usageRecord = UsageRecord::create($this->validatedData($request));

        return response()->json($usageRecord->load(['user', 'subscription']), 201);
    }

    public function show(UsageRecord $usageRecord)
    {
        return $usageRecord->load(['user', 'subscription']);
    }

    public function update(Request $request, UsageRecord $usageRecord)
    {
        $usageRecord->update($this->validatedData($request));

        return $usageRecord->fresh()->load(['user', 'subscription']);
    }

    public function destroy(UsageRecord $usageRecord)
    {
        $usageRecord->delete();

        return response()->noContent();
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'subscription_id' => ['required', 'exists:subscriptions,id'],
            'user_id' => ['required', 'exists:users,id'],
            'metric' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'integer', 'min:0'],
            'recorded_at' => ['required', 'date'],
            'metadata' => ['nullable', 'array'],
        ]);
    }
}
