<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Tests for authentication: login, logout, registration.
 */
class AuthTest extends TestCase
{
    use RefreshDatabase;

    // ─────────────────────────────────────────────
    // Login page
    // ─────────────────────────────────────────────

    /** @test */
    public function test_login_page_is_accessible_by_guests(): void
    {
        $this->get(route('login'))->assertOk();
    }

    /** @test */
    public function test_authenticated_user_is_redirected_from_login(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'user']))
            ->get(route('login'))
            ->assertRedirect();
    }

    // ─────────────────────────────────────────────
    // Login (POST)
    // ─────────────────────────────────────────────

    /** @test */
    public function test_user_can_login_with_correct_credentials(): void
    {
        $user = User::factory()->create([
            'role'     => 'user',
            'password' => bcrypt('secret123'),
        ]);

        $this->post(route('login'), [
            'email'    => $user->email,
            'password' => 'secret123',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function test_user_cannot_login_with_wrong_password(): void
    {
        $user = User::factory()->create(['role' => 'user', 'password' => bcrypt('correct')]);

        $this->post(route('login'), [
            'email'    => $user->email,
            'password' => 'wrong',
        ])->assertSessionHasErrors(['email']);

        $this->assertGuest();
    }

    /** @test */
    public function test_login_requires_email_and_password(): void
    {
        $this->post(route('login'), [])
            ->assertSessionHasErrors(['email', 'password']);
    }

    // ─────────────────────────────────────────────
    // Logout
    // ─────────────────────────────────────────────

    /** @test */
    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)
            ->post(route('logout'))
            ->assertRedirect('/');

        $this->assertGuest();
    }

    // ─────────────────────────────────────────────
    // Registration page
    // ─────────────────────────────────────────────

    /** @test */
    public function test_registration_page_is_accessible_by_guests(): void
    {
        $this->get(route('register'))->assertOk();
    }

    /** @test */
    public function test_user_can_register_with_valid_data(): void
    {
        // Ensure at least one existing user so new registrant is not auto-promoted to admin
        User::factory()->create(['role' => 'admin']);

        $this->post(route('register'), [
            'first_name'            => 'Ada',
            'middle_name'           => 'Grace',
            'last_name'             => 'Smith',
            'email'                 => 'ada@example.com',
            'matric_no'             => '123456',
            'password'              => 'Password1!',
            'password_confirmation' => 'Password1!',
        ])->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('users', [
            'email'      => 'ada@example.com',
            'first_name' => 'Ada',
        ]);
    }

    /** @test */
    public function test_registration_rejects_duplicate_email(): void
    {
        User::factory()->create(['role' => 'admin', 'email' => 'taken@example.com']);

        $this->post(route('register'), [
            'first_name'            => 'Test',
            'last_name'             => 'User',
            'email'                 => 'taken@example.com',
            'matric_no'             => '999001',
            'password'              => 'Password1!',
            'password_confirmation' => 'Password1!',
        ])->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function test_new_registered_user_gets_user_role_by_default(): void
    {
        // Pre-create an admin so the "first user = admin" check doesn't fire
        User::factory()->create(['role' => 'admin']);

        $this->post(route('register'), [
            'first_name'            => 'Role',
            'last_name'             => 'Check',
            'email'                 => 'rolecheck@example.com',
            'matric_no'             => '654321',
            'password'              => 'Password1!',
            'password_confirmation' => 'Password1!',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'rolecheck@example.com',
            'role'  => 'user',
        ]);
    }
}
