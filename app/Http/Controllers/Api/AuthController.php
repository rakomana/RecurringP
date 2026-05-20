<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create($request->safe()->except('token_name'));

        return response()->json($this->tokenResponse($user, $request->input('token_name', 'api-token')), 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::query()
            ->where('email', $request->validated('email'))
            ->first();

        if (! $user || ! Hash::check($request->validated('password'), $user->password)) {
            return response()->json([
                'message' => 'The provided credentials are incorrect.',
            ], 401);
        }

        return response()->json($this->tokenResponse($user, $request->input('token_name', 'api-token')));
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json($request->user()->load('companies'));
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()?->token()?->revoke();

        return response()->json([
            'message' => 'Logged out successfully.',
        ]);
    }

    private function tokenResponse(User $user, string $tokenName): array
    {
        $token = $user->createToken($tokenName);

        return [
            'user' => $user->load('companies'),
            'access_token' => $token->accessToken,
            'token_type' => $token->tokenType,
            'expires_in' => $token->expiresIn,
        ];
    }
}
