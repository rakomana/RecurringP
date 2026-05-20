<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        return User::query()->with('companies')->latest()->paginate();
    }

    public function store(UserRequest $request)
    {
        $user = User::create($request->safe()->except('company_ids'));

        if ($request->filled('company_ids')) {
            $user->companies()->sync($this->membershipPayload($request->validated('company_ids')));
        }

        return response()->json($user->load('companies'), 201);
    }

    public function show(User $user)
    {
        return $user->load(['companies', 'subscriptions', 'invoices', 'payments', 'usageRecords']);
    }

    public function update(UserRequest $request, User $user)
    {
        $user->update($request->safe()->except('company_ids'));

        if ($request->has('company_ids')) {
            $user->companies()->sync($this->membershipPayload($request->validated('company_ids', [])));
        }

        return $user->fresh()->load('companies');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response()->noContent();
    }

    private function membershipPayload(array $companyIds): array
    {
        return collect($companyIds)
            ->mapWithKeys(fn (int $companyId) => [$companyId => ['role' => 'member']])
            ->all();
    }
}
