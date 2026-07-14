<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    private function user(): User
    {
        return User::factory()->create(['role' => 'user']);
    }

    // ─────────────────────────────────────────────
    // edit
    // ─────────────────────────────────────────────

    /** @test */
    public function test_authenticated_user_can_view_profile_edit_page(): void
    {
        $this->actingAs($this->user())
            ->get(route('profile.edit'))
            ->assertOk()
            ->assertViewIs('profile.edit');
    }

    /** @test */
    public function test_guest_is_redirected_from_profile_edit(): void
    {
        $this->get(route('profile.edit'))
            ->assertRedirect(route('login'));
    }

    // ─────────────────────────────────────────────
    // update
    // ─────────────────────────────────────────────

    /** @test */
    public function test_user_can_update_their_profile_details(): void
    {
        $user = $this->user();

        $this->actingAs($user)
            ->patch(route('profile.update'), [
                'first_name'  => 'Newfirst',
                'middle_name' => 'Newmiddle',
                'last_name'   => 'Newlast',
                'email'       => 'new@example.com',
                'matric_no'   => '999999',
            ])
            ->assertRedirect(route('profile.edit'));

        $this->assertDatabaseHas('users', [
            'id'          => $user->id,
            'first_name'  => 'Newfirst',
            'middle_name' => 'Newmiddle',
            'last_name'   => 'Newlast',
            'email'       => 'new@example.com',
            'matric_no'   => '999999',
        ]);
    }

    /** @test */
    public function test_changing_email_clears_email_verified_at(): void
    {
        $user = User::factory()->create([
            'role'              => 'user',
            'email'             => 'original@example.com',
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user)
            ->patch(route('profile.update'), [
                'first_name'  => $user->first_name,
                'middle_name' => $user->middle_name,
                'last_name'   => $user->last_name,
                'email'       => 'changed@example.com',
                'matric_no'   => $user->matric_no,
            ]);

        $this->assertNull($user->fresh()->email_verified_at);
    }

    /** @test */
    public function test_keeping_same_email_does_not_clear_verified_at(): void
    {
        $user = User::factory()->create([
            'role'              => 'user',
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user)
            ->patch(route('profile.update'), [
                'first_name'  => 'Updatedfirst',
                'middle_name' => $user->middle_name,
                'last_name'   => $user->last_name,
                'email'       => $user->email,
                'matric_no'   => $user->matric_no,
            ]);

        $this->assertNotNull($user->fresh()->email_verified_at);
    }

    /** @test */
    public function test_profile_update_sets_status_session_variable(): void
    {
        $user = $this->user();

        $this->actingAs($user)
            ->patch(route('profile.update'), [
                'first_name'  => $user->first_name,
                'middle_name' => $user->middle_name,
                'last_name'   => $user->last_name,
                'email'       => $user->email,
                'matric_no'   => $user->matric_no,
            ])
            ->assertSessionHas('status', 'profile-updated');
    }

    /** @test */
    public function test_profile_update_validates_first_name_is_required(): void
    {
        $user = $this->user();

        $this->actingAs($user)
            ->patch(route('profile.update'), [
                'first_name'  => '',
                'middle_name' => $user->middle_name,
                'last_name'   => $user->last_name,
                'email'       => $user->email,
                'matric_no'   => $user->matric_no,
            ])
            ->assertSessionHasErrors(['first_name']);
    }

    /** @test */
    public function test_profile_update_validates_email_is_required_and_valid(): void
    {
        $user = $this->user();

        $this->actingAs($user)
            ->patch(route('profile.update'), [
                'first_name'  => 'Validfirst',
                'middle_name' => $user->middle_name,
                'last_name'   => $user->last_name,
                'email'       => 'not-an-email',
                'matric_no'   => $user->matric_no,
            ])
            ->assertSessionHasErrors(['email']);
    }

    // ─────────────────────────────────────────────
    // destroy
    // ─────────────────────────────────────────────

    /** @test */
    public function test_user_can_delete_their_account_with_correct_password(): void
    {
        $user = User::factory()->create([
            'role'     => 'user',
            'password' => bcrypt('secret123'),
        ]);

        $this->actingAs($user)
            ->delete(route('profile.destroy'), ['password' => 'secret123'])
            ->assertRedirect('/');

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    /** @test */
    public function test_user_cannot_delete_account_with_wrong_password(): void
    {
        $user = User::factory()->create([
            'role'     => 'user',
            'password' => bcrypt('correct-password'),
        ]);

        $this->actingAs($user)
            ->delete(route('profile.destroy'), ['password' => 'wrong-password'])
            ->assertSessionHasErrors(['password']);

        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    /** @test */
    public function test_account_deletion_logs_out_the_user(): void
    {
        $user = User::factory()->create([
            'role'     => 'user',
            'password' => bcrypt('mypassword'),
        ]);

        $this->actingAs($user)
            ->delete(route('profile.destroy'), ['password' => 'mypassword']);

        $this->assertGuest();
    }

    /** @test */
    public function test_guest_cannot_delete_a_profile(): void
    {
        $this->delete(route('profile.destroy'), ['password' => 'anything'])
            ->assertRedirect(route('login'));
    }
}
