<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        return User::query()->latest()->paginate();
    }

    public function store(Request $request)
    {
        $user = User::create($this->validatedData($request));

        return response()->json($user, 201);
    }

    public function show(User $user)
    {
        return $user->load(['subscriptions', 'invoices', 'payments', 'usageRecords']);
    }

    public function update(Request $request, User $user)
    {
        $user->update($this->validatedData($request, $user));

        return $user->fresh();
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response()->noContent();
    }

    private function validatedData(Request $request, ?User $user = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user)],
            'password' => [$user ? 'sometimes' : 'required', 'string', 'min:8'],
        ]);
    }
}
