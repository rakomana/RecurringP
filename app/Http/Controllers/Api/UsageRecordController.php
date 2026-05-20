<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UsageRecordRequest;
use App\Models\UsageRecord;

class UsageRecordController extends Controller
{
    public function index()
    {
        return UsageRecord::query()->with(['company', 'user', 'subscription'])->latest()->paginate();
    }

    public function store(UsageRecordRequest $request)
    {
        $usageRecord = UsageRecord::create($request->validated());

        return response()->json($usageRecord->load(['company', 'user', 'subscription']), 201);
    }

    public function show(UsageRecord $usageRecord)
    {
        return $usageRecord->load(['company', 'user', 'subscription']);
    }

    public function update(UsageRecordRequest $request, UsageRecord $usageRecord)
    {
        $usageRecord->update($request->validated());

        return $usageRecord->fresh()->load(['company', 'user', 'subscription']);
    }

    public function destroy(UsageRecord $usageRecord)
    {
        $usageRecord->delete();

        return response()->noContent();
    }

}
