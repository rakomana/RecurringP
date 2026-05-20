<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuthConfigTest extends TestCase
{
    public function test_api_guard_is_configured_for_passport(): void
    {
        $this->assertSame('passport', config('auth.guards.api.driver'));
        $this->assertSame('users', config('auth.guards.api.provider'));
    }
}
