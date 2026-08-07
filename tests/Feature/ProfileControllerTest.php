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
    // edit (profile page)
    // ─────────────────────────────────────────────

    /** @test */
    public function test_authenticated_user_can_view_profile_page(): void
    {
        $this->actingAs($this->user())
            ->get(route('profile.edit'))
            ->assertOk()
            ->assertViewIs('profile.edit');
    }

    /** @test */
    public function test_guest_cannot_view_profile_page(): void
    {
        $this->get(route('profile.edit'))
            ->assertRedirect(route('login'));
    }

    // ─────────────────────────────────────────────
    // update
    // ─────────────────────────────────────────────

    /** @test */
    public function test_user_can_update_name_and_email(): void
    {
        $user = $this->user();

        $this->actingAs($user)
            ->patch(route('profile.update'), [
                'name'  => 'New Name',
                'email' => 'new@example.com',
            ])
            ->assertRedirect(route('profile.edit'));

        $this->assertDatabaseHas('users', [
            'id'    => $user->id,
            'name'  => 'New Name',
            'email' => 'new@example.com',
        ]);
    }

    /** @test */
    public function test_user_can_save_whatsapp_number_from_profile(): void
    {
        $user = $this->user();

        $this->actingAs($user)
            ->patch(route('profile.update'), [
                'name'             => $user->name,
                'email'            => $user->email,
                'whatsapp_number'  => '+2348012345678',
            ])
            ->assertRedirect(route('profile.edit'));

        $this->assertDatabaseHas('users', [
            'id'              => $user->id,
            'whatsapp_number' => '+2348012345678',
        ]);
    }

    /** @test */
    public function test_whatsapp_number_must_be_valid_international_format(): void
    {
        $user = $this->user();

        $this->actingAs($user)
            ->patch(route('profile.update'), [
                'name'            => $user->name,
                'email'           => $user->email,
                'whatsapp_number' => 'not-a-number',
            ])
            ->assertSessionHasErrors(['whatsapp_number']);
    }

    /** @test */
    public function test_whatsapp_number_is_optional_on_profile_update(): void
    {
        $user = $this->user();

        $this->actingAs($user)
            ->patch(route('profile.update'), [
                'name'  => $user->name,
                'email' => $user->email,
            ])
            ->assertRedirect(route('profile.edit'))
            ->assertSessionHasNoErrors();
    }

    /** @test */
    public function test_profile_update_requires_name_and_email(): void
    {
        $this->actingAs($this->user())
            ->patch(route('profile.update'), [])
            ->assertSessionHasErrors(['name', 'email']);
    }

    /** @test */
    public function test_email_must_be_unique_on_profile_update(): void
    {
        $user  = $this->user();
        $other = User::factory()->create(['email' => 'taken@example.com']);

        $this->actingAs($user)
            ->patch(route('profile.update'), [
                'name'  => $user->name,
                'email' => 'taken@example.com',
            ])
            ->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function test_user_can_keep_their_own_email_on_update(): void
    {
        $user = $this->user();

        $this->actingAs($user)
            ->patch(route('profile.update'), [
                'name'  => 'Updated Name',
                'email' => $user->email,
            ])
            ->assertRedirect(route('profile.edit'))
            ->assertSessionHasNoErrors();
    }

    // ─────────────────────────────────────────────
    // destroy
    // ─────────────────────────────────────────────

    /** @test */
    public function test_user_can_delete_their_own_account(): void
    {
        $user = User::factory()->create([
            'role'     => 'user',
            'password' => bcrypt('password'),
        ]);

        $this->actingAs($user)
            ->delete(route('profile.destroy'), ['password' => 'password'])
            ->assertRedirect('/');

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    /** @test */
    public function test_account_deletion_requires_correct_password(): void
    {
        $user = User::factory()->create(['password' => bcrypt('correct')]);

        $this->actingAs($user)
            ->delete(route('profile.destroy'), ['password' => 'wrong'])
            ->assertSessionHasErrors(['password']);

        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }
}
