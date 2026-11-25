<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config(['database.connections.user_sqlite.database' => ':memory:']);
        Schema::connection('user_sqlite')->create('users', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role')->default('customer');
            $table->string('remember_token')->nullable();
            $table->timestamps();
        });
    }

    public function test_registration_defaults_to_customer_and_password_can_be_used_to_log_in(): void
    {
        $this->postJson('/api/register', [
            'name' => 'Test Customer',
            'email' => 'customer@example.com',
            'password' => 'correct-password',
            'password_confirmation' => 'correct-password',
        ])
            ->assertCreated()
            ->assertJsonStructure(['token', 'token_type', 'role', 'user'])
            ->assertJsonPath('token_type', 'bearer')
            ->assertJsonPath('role', 'customer');

        $this->postJson('/api/login', [
            'email' => 'customer@example.com',
            'password' => 'correct-password',
        ])->assertOk()
            ->assertJsonPath('role', 'customer');
    }

    public function test_missing_or_invalid_api_token_returns_json_401(): void
    {
        $this->get('/api/admin/users', ['Accept' => 'text/html'])
            ->assertUnauthorized()
            ->assertJson(['message' => 'Unauthenticated.']);

        $this->get('/api/admin/users', [
            'Accept' => 'text/html',
            'Authorization' => 'Bearer invalid-token',
        ])
            ->assertUnauthorized()
            ->assertJson(['message' => 'Unauthenticated.']);
    }

    public function test_non_admin_roles_cannot_manage_users(): void
    {
        $target = User::factory()->create();

        foreach (['customer', 'inventory_manager'] as $role) {
            $user = User::factory()->create(['role' => $role]);
            $headers = ['Authorization' => 'Bearer '.JWTAuth::fromUser($user)];

            $this->withHeaders($headers)->getJson('/api/admin/users')->assertForbidden();
            $this->withHeaders($headers)->patchJson("/api/admin/users/{$target->id}/role", [
                'role' => 'admin',
            ])->assertForbidden();
            $this->withHeaders($headers)->deleteJson("/api/admin/users/{$target->id}")->assertForbidden();
        }
    }

    public function test_admin_can_manage_users_but_cannot_delete_themselves(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $target = User::factory()->create();
        $headers = ['Authorization' => 'Bearer '.JWTAuth::fromUser($admin)];

        $this->withHeaders($headers)->getJson('/api/admin/users')->assertOk();

        $this->withHeaders($headers)->patchJson("/api/admin/users/{$target->id}/role", [
            'role' => 'inventory_manager',
        ])
            ->assertOk()
            ->assertJsonPath('user.role', 'inventory_manager');

        $this->withHeaders($headers)->patchJson("/api/admin/users/{$target->id}/role", [
            'role' => 'unknown',
        ])->assertUnprocessable();

        $this->withHeaders($headers)->deleteJson("/api/admin/users/{$target->id}")->assertOk();
        $this->withHeaders($headers)->deleteJson("/api/admin/users/{$admin->id}")->assertUnprocessable();
    }
}
