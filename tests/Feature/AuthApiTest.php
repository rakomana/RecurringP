<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app(ClientRepository::class)->createPersonalAccessGrantClient('Test Personal Access Client', 'users');
    }

    public function test_user_can_register_and_receive_access_token(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'password',
            'token_name' => 'test-token',
        ]);

        $response->assertCreated()
            ->assertJsonPath('user.email', 'jane@example.com')
            ->assertJsonPath('token_type', 'Bearer')
            ->assertJsonStructure([
                'access_token',
                'expires_in',
                'user' => ['id', 'name', 'email'],
            ]);
    }

    public function test_user_can_login_and_receive_access_token(): void
    {
        User::factory()->create([
            'email' => 'jane@example.com',
            'password' => 'password',
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'jane@example.com',
            'password' => 'password',
        ]);

        $response->assertOk()
            ->assertJsonPath('user.email', 'jane@example.com')
            ->assertJsonPath('token_type', 'Bearer')
            ->assertJsonStructure(['access_token']);
    }

    public function test_login_rejects_invalid_credentials(): void
    {
        User::factory()->create([
            'email' => 'jane@example.com',
            'password' => 'password',
        ]);

        $this->postJson('/api/auth/login', [
            'email' => 'jane@example.com',
            'password' => 'wrong-password',
        ])->assertUnauthorized()
            ->assertJsonPath('message', 'The provided credentials are incorrect.');
    }

    public function test_authenticated_user_can_view_profile(): void
    {
        $user = User::factory()->create();

        Passport::actingAs($user);

        $this->getJson('/api/auth/me')
            ->assertOk()
            ->assertJsonPath('id', $user->id)
            ->assertJsonPath('email', $user->email);
    }

    public function test_protected_routes_require_authentication(): void
    {
        $this->getJson('/api/companies')
            ->assertUnauthorized();
    }
}
