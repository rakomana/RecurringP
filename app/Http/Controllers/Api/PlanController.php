<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PlanRequest;
use App\Models\Plan;

class PlanController extends Controller
{
    public function index()
    {
        return Plan::query()->with('company')->latest()->paginate();
    }

    public function store(PlanRequest $request)
    {
        $plan = Plan::create($request->validated());

        return response()->json($plan, 201);
    }

    public function show(Plan $plan)
    {
        return $plan->load(['company', 'subscriptions']);
    }

    public function update(PlanRequest $request, Plan $plan)
    {
        $plan->update($request->validated());

        return $plan->fresh();
    }

    public function destroy(Plan $plan)
    {
        $plan->delete();

        return response()->noContent();
    }

}
