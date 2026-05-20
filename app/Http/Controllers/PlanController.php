<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PlanController extends Controller
{
    public function index()
    {
        return Plan::query()->latest()->paginate();
    }

    public function store(Request $request)
    {
        $plan = Plan::create($this->validatedData($request));

        return response()->json($plan, 201);
    }

    public function show(Plan $plan)
    {
        return $plan->load('subscriptions');
    }

    public function update(Request $request, Plan $plan)
    {
        $plan->update($this->validatedData($request, $plan));

        return $plan->fresh();
    }

    public function destroy(Plan $plan)
    {
        $plan->delete();

        return response()->noContent();
    }

    private function validatedData(Request $request, ?Plan $plan = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('plans', 'slug')->ignore($plan)],
            'description' => ['nullable', 'string'],
            'amount' => ['required', 'integer', 'min:0'],
            'currency' => ['required', 'string', 'size:3'],
            'billing_interval' => ['required', 'string', 'in:day,week,month,year'],
            'trial_days' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'metadata' => ['nullable', 'array'],
        ]);
    }
}
