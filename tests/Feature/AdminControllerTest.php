<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminControllerTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function regularUser(): User
    {
        return User::factory()->create(['role' => 'user']);
    }

    private function supportUser(): User
    {
        return User::factory()->create(['role' => 'support']);
    }

    // ─────────────────────────────────────────────
    // index
    // ─────────────────────────────────────────────

    /** @test */
    public function test_admin_can_view_users_list(): void
    {
        User::factory()->count(3)->create();

        $this->actingAs($this->admin())
            ->get(route('admin.users'))
            ->assertOk()
            ->assertViewIs('admin.users')
            ->assertViewHas('users');
    }

    /** @test */
    public function test_non_admin_cannot_access_users_list(): void
    {
        $this->actingAs($this->regularUser())
            ->get(route('admin.users'))
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('error');
    }

    /** @test */
    public function test_guest_is_redirected_from_users_list(): void
    {
        $this->get(route('admin.users'))
            ->assertRedirect(route('login'));
    }

    // ─────────────────────────────────────────────
    // updateRole
    // ─────────────────────────────────────────────

    /** @test */
    public function test_admin_can_update_a_users_role(): void
    {
        $admin  = $this->admin();
        $target = $this->regularUser();

        $this->actingAs($admin)
            ->patch(route('admin.users.update-role', $target), ['role' => 'support'])
            ->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id'   => $target->id,
            'role' => 'support',
        ]);
    }

    /** @test */
    public function test_admin_cannot_change_their_own_role(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->patch(route('admin.users.update-role', $admin), ['role' => 'user'])
            ->assertSessionHas('error');

        // Role should remain admin
        $this->assertDatabaseHas('users', ['id' => $admin->id, 'role' => 'admin']);
    }

    /** @test */
    public function test_role_update_validates_allowed_roles(): void
    {
        $admin  = $this->admin();
        $target = $this->regularUser();

        $this->actingAs($admin)
            ->patch(route('admin.users.update-role', $target), ['role' => 'superadmin'])
            ->assertSessionHasErrors(['role']);
    }

    /** @test */
    public function test_role_field_is_required(): void
    {
        $admin  = $this->admin();
        $target = $this->regularUser();

        $this->actingAs($admin)
            ->patch(route('admin.users.update-role', $target), [])
            ->assertSessionHasErrors(['role']);
    }

    /** @test */
    public function test_non_admin_cannot_update_user_roles(): void
    {
        $actor  = $this->regularUser();
        $target = $this->supportUser();

        $this->actingAs($actor)
            ->patch(route('admin.users.update-role', $target), ['role' => 'user'])
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('error');
    }

    /** @test */
    public function test_admin_can_promote_user_to_admin(): void
    {
        $admin  = $this->admin();
        $target = $this->regularUser();

        $this->actingAs($admin)
            ->patch(route('admin.users.update-role', $target), ['role' => 'admin'])
            ->assertRedirect();

        $this->assertDatabaseHas('users', ['id' => $target->id, 'role' => 'admin']);
    }

    // ─────────────────────────────────────────────
    // destroy
    // ─────────────────────────────────────────────

    /** @test */
    public function test_admin_can_delete_another_user(): void
    {
        $admin  = $this->admin();
        $target = $this->regularUser();

        $this->actingAs($admin)
            ->delete(route('admin.users.destroy', $target))
            ->assertRedirect();

        $this->assertDatabaseMissing('users', ['id' => $target->id]);
    }

    /** @test */
    public function test_admin_cannot_delete_their_own_account(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->delete(route('admin.users.destroy', $admin))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    /** @test */
    public function test_non_admin_cannot_delete_a_user(): void
    {
        $actor  = $this->regularUser();
        $target = $this->regularUser();

        $this->actingAs($actor)
            ->delete(route('admin.users.destroy', $target))
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('error');
    }

    /** @test */
    public function test_success_flash_is_shown_after_role_update(): void
    {
        $admin  = $this->admin();
        $target = $this->regularUser();

        $this->actingAs($admin)
            ->patch(route('admin.users.update-role', $target), ['role' => 'support'])
            ->assertSessionHas('success');
    }

    /** @test */
    public function test_success_flash_is_shown_after_user_deletion(): void
    {
        $admin  = $this->admin();
        $target = $this->regularUser();

        $this->actingAs($admin)
            ->delete(route('admin.users.destroy', $target))
            ->assertSessionHas('success');
    }
}
