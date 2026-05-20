<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyRequest;
use App\Models\Company;

class CompanyController extends Controller
{
    public function index()
    {
        return Company::query()->withCount('users')->latest()->paginate();
    }

    public function store(CompanyRequest $request)
    {
        $company = Company::create($request->safe()->except('user_ids'));

        if ($request->filled('user_ids')) {
            $company->users()->sync($this->membershipPayload($request->validated('user_ids')));
        }

        return response()->json($company->load('users'), 201);
    }

    public function show(Company $company)
    {
        return $company->load([
            'users',
            'plans',
            'subscriptions',
            'invoices',
            'payments',
            'paymentAttempts',
            'webhookEvents',
            'usageRecords',
        ]);
    }

    public function update(CompanyRequest $request, Company $company)
    {
        $company->update($request->safe()->except('user_ids'));

        if ($request->has('user_ids')) {
            $company->users()->sync($this->membershipPayload($request->validated('user_ids', [])));
        }

        return $company->fresh()->load('users');
    }

    public function destroy(Company $company)
    {
        $company->delete();

        return response()->noContent();
    }

    private function membershipPayload(array $userIds): array
    {
        return collect($userIds)
            ->mapWithKeys(fn (int $userId) => [$userId => ['role' => 'member']])
            ->all();
    }
}
