<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiSanctumAuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Slide 12: Unauthenticated request to protected API endpoint receives 401 Unauthorized.
     */
    public function test_unauthenticated_request_to_protected_api_is_rejected(): void
    {
        $response = $this->getJson('/api/shipments');

        $response->assertStatus(401);
    }

    /**
     * Test Slide 12: Valid credentials issue a Sanctum Bearer token.
     */
    public function test_login_with_valid_credentials_issues_bearer_token(): void
    {
        $user = User::factory()->create([
            'email'    => 'testuser@cargotrace.test',
            'password' => bcrypt('secret123'),
            'role'     => 'operator',
        ]);

        $response = $this->postJson('/api/login', [
            'email'    => 'testuser@cargotrace.test',
            'password' => 'secret123',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data'    => [
                'token_type' => 'Bearer',
                'user'       => [
                    'email' => 'testuser@cargotrace.test',
                    'role'  => 'operator',
                ],
            ],
        ]);
        $this->assertNotEmpty($response->json('data.access_token'));
    }

    /**
     * Test Slide 12: Invalid credentials return 401 Unauthorized.
     */
    public function test_login_with_invalid_credentials_returns_401(): void
    {
        User::factory()->create([
            'email'    => 'testuser@cargotrace.test',
            'password' => bcrypt('correct-password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email'    => 'testuser@cargotrace.test',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(401);
        $response->assertJson([
            'success' => false,
        ]);
    }

    /**
     * Test Slide 12: Authenticated user can retrieve their profile.
     */
    public function test_authenticated_user_can_retrieve_profile(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/user');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data'    => [
                'id'    => $user->id,
                'email' => $user->email,
                'role'  => 'admin',
            ],
        ]);
    }

    /**
     * Test Slide 12: Logout revokes the token.
     */
    public function test_logout_revokes_token(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/logout');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertCount(0, $user->tokens);
    }
}
