<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\SessionToken;
use App\Models\Organization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_signs_up_a_new_user_and_organization(): void
    {
        $payload = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'Secret123!',
            'confirm_password' => 'Secret123!',
            'organization' => 'Acme Corp',
        ];

        $response = $this->postJson('/api/auth/signup', $payload);

        $response->assertOk() // AuthController returns success response which defaults to 200
                 ->assertJsonPath('success', true)
                 ->assertJsonStructure([
                     'data' => [
                         'user' => ['id', 'name', 'email', 'organization_id'],
                         'organization' => ['id', 'name', 'created_at'],
                     ]
                 ]);

        $this->assertDatabaseHas('users', ['email' => 'john@example.com', 'is_active' => false]);
        $this->assertDatabaseHas('organizations', ['name' => 'Acme Corp']);
        
        // Ensure a verification token was generated
        $user = User::where('email', 'john@example.com')->first();
        $this->assertDatabaseHas('session_tokens', [
            'type' => 'signup_verification_token',
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function it_verifies_signup_using_verification_code(): void
    {
        $user = User::factory()->create([
            'email' => 'jane@example.com',
            'is_active' => false,
        ]);

        $otp = SessionToken::generate('signup_verification_token', $user);

        $response = $this->postJson('/api/auth/verify-signup', [
            'verification_code' => $otp,
        ]);

        $response->assertOk()
                 ->assertJsonPath('success', true)
                 ->assertJsonPath('data.user.id', $user->id);

        $this->assertTrue($user->fresh()->is_active);

        // Verification token should be deleted
        $this->assertDatabaseMissing('session_tokens', [
            'token' => $otp,
            'type' => 'signup_verification_token',
        ]);
    }

    /** @test */
    public function it_logs_in_an_active_user_and_returns_access_token(): void
    {
        $user = User::factory()->create([
            'email' => 'jane@example.com',
            'password' => bcrypt('password123'),
            'is_active' => true,
        ]);

        $payload = [
            'email' => 'jane@example.com',
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/auth/login', $payload);

        $response->assertOk()
                 ->assertJsonPath('success', true)
                 ->assertJsonStructure([
                     'data' => [
                         'access_token',
                         'user' => ['id', 'name', 'email'],
                     ]
                 ]);

        // Verify token in DB
        $token = $response->json('data.access_token');
        $this->assertNotNull($token);
        $this->assertDatabaseHas('session_tokens', [
            'token' => $token,
            'type' => 'access_token',
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function it_prevents_login_for_inactive_users(): void
    {
        $user = User::factory()->create([
            'email' => 'inactive@example.com',
            'password' => bcrypt('password123'),
            'is_active' => false,
        ]);

        $payload = [
            'email' => 'inactive@example.com',
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/auth/login', $payload);

        $response->assertStatus(403)
                 ->assertJsonPath('success', false)
                 ->assertJsonPath('message', 'Your account is not activated. Please verify your email first.');
    }

    /** @test */
    public function it_logs_out_and_revokes_the_access_token(): void
    {
        $user = User::factory()->create([
            'email' => 'bob@example.com',
            'password' => bcrypt('mysecurepwd'),
            'is_active' => true,
        ]);

        $loginResponse = $this->postJson('/api/auth/login', [
            'email' => 'bob@example.com',
            'password' => 'mysecurepwd',
        ]);
        $token = $loginResponse->json('data.access_token');

        $response = $this->withHeaders([
                'Authorization' => "Bearer $token",
            ])->postJson('/api/auth/logout');

        $response->assertOk()
                 ->assertJsonPath('success', true);

        // Token should be deleted
        $this->assertDatabaseMissing('session_tokens', [
            'token' => $token,
            'type' => 'access_token',
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function it_initiates_forgot_password_and_generates_otp(): void
    {
        $user = User::factory()->create([
            'email' => 'forgot@example.com',
            'is_active' => true,
        ]);

        $response = $this->postJson('/api/auth/forgot-password', [
            'email' => 'forgot@example.com',
        ]);

        $response->assertOk()
                 ->assertJsonPath('success', true);

        $this->assertDatabaseHas('session_tokens', [
            'type' => 'forgot_password_token',
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function it_resets_password_using_forgot_password_token(): void
    {
        $user = User::factory()->create([
            'email' => 'reset@example.com',
            'is_active' => true,
            'password' => bcrypt('old_password'),
        ]);

        $otp = SessionToken::generate('forgot_password_token', $user);

        $response = $this->postJson('/api/auth/reset-password', [
            'verification_code' => $otp,
            'password' => 'NewPassword123!',
            'confirm_password' => 'NewPassword123!',
        ]);

        $response->assertOk()
                 ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('session_tokens', [
            'token' => $otp,
            'type' => 'forgot_password_token',
        ]);
    }

    /** @test */
    public function it_updates_password_when_authenticated(): void
    {
        $user = User::factory()->create([
            'email' => 'updatepwd@example.com',
            'password' => bcrypt('OldPassword123!'),
            'is_active' => true,
        ]);

        $token = SessionToken::generate('access_token', $user);

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->patchJson('/api/auth/update-password', [
            'old_password' => 'OldPassword123!',
            'new_password' => 'NewPassword999!',
            'confirm_password' => 'NewPassword999!',
        ]);

        $response->assertOk()
                 ->assertJsonPath('success', true);
    }

    /** @test */
    public function it_reads_authenticated_profile(): void
    {
        $user = User::factory()->create([
            'email' => 'profile@example.com',
            'is_active' => true,
        ]);

        $token = SessionToken::generate('access_token', $user);

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->getJson('/api/auth/read');

        $response->assertOk()
                 ->assertJsonPath('success', true);
    }

    /** @test */
    public function it_updates_profile(): void
    {
        $user = User::factory()->create([
            'email' => 'updateprofile@example.com',
            'name' => 'Original Name',
            'is_active' => true,
        ]);

        $token = SessionToken::generate('access_token', $user);

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->putJson('/api/auth/update', [
            'name' => 'Updated Name',
        ]);

        $response->assertOk()
                 ->assertJsonPath('success', true)
                 ->assertJsonPath('data.user.name', 'Updated Name');

        $this->assertEquals('Updated Name', $user->fresh()->name);
    }
}
