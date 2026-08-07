<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Tests for authentication flows: login, logout, registration.
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
        $this->actingAs(User::factory()->create())
            ->get(route('login'))
            ->assertRedirect();
    }

    // ─────────────────────────────────────────────
    // Login (POST)
    // ─────────────────────────────────────────────

    /** @test */
    public function test_user_can_login_with_correct_credentials(): void
    {
        $user = User::factory()->create(['password' => bcrypt('secret123')]);

        $this->post(route('login'), [
            'email'    => $user->email,
            'password' => 'secret123',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function test_user_cannot_login_with_wrong_password(): void
    {
        $user = User::factory()->create(['password' => bcrypt('correct')]);

        $this->post(route('login'), [
            'email'    => $user->email,
            'password' => 'wrong',
        ])->assertSessionHasErrors(['email']);

        $this->assertGuest();
    }

    /** @test */
    public function test_user_cannot_login_with_nonexistent_email(): void
    {
        $this->post(route('login'), [
            'email'    => 'nobody@example.com',
            'password' => 'anything',
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
        $user = User::factory()->create();

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
        $this->post(route('register'), [
            'name'                  => 'New User',
            'email'                 => 'newuser@example.com',
            'password'              => 'Password1!',
            'password_confirmation' => 'Password1!',
        ])->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('users', [
            'email' => 'newuser@example.com',
            'name'  => 'New User',
        ]);
    }

    /** @test */
    public function test_registration_requires_all_fields(): void
    {
        $this->post(route('register'), [])
            ->assertSessionHasErrors(['name', 'email', 'password']);
    }

    /** @test */
    public function test_registration_rejects_duplicate_email(): void
    {
        User::factory()->create(['email' => 'taken@example.com']);

        $this->post(route('register'), [
            'name'                  => 'Another',
            'email'                 => 'taken@example.com',
            'password'              => 'Password1!',
            'password_confirmation' => 'Password1!',
        ])->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function test_registration_requires_password_confirmation(): void
    {
        $this->post(route('register'), [
            'name'                  => 'Test',
            'email'                 => 'test@example.com',
            'password'              => 'Password1!',
            'password_confirmation' => 'Mismatch1!',
        ])->assertSessionHasErrors(['password']);
    }

    /** @test */
    public function test_new_registered_user_gets_user_role_by_default(): void
    {
        $this->post(route('register'), [
            'name'                  => 'Role Check',
            'email'                 => 'rolecheck@example.com',
            'password'              => 'Password1!',
            'password_confirmation' => 'Password1!',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'rolecheck@example.com',
            'role'  => 'user',
        ]);
    }
}
