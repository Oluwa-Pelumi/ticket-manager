<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminControllerTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function support(): User
    {
        return User::factory()->create(['role' => 'support']);
    }

    private function regularUser(): User
    {
        return User::factory()->create(['role' => 'user']);
    }

    // ─────────────────────────────────────────────
    // index (user list)
    // ─────────────────────────────────────────────

    /** @test */
    public function test_admin_can_view_users_page(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.users'))
            ->assertOk()
            ->assertViewIs('admin.users')
            ->assertViewHas('users');
    }

    /** @test */
    public function test_users_page_includes_ticket_counts(): void
    {
        $admin  = $this->admin();
        $user   = $this->regularUser();
        Ticket::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->actingAs($admin)->get(route('admin.users'))->assertOk();
        $users    = $response->viewData('users');

        $found = $users->firstWhere('id', $user->id);
        $this->assertNotNull($found);
        $this->assertEquals(3, $found->tickets_count);
    }

    /** @test */
    public function test_non_admin_cannot_view_users_page(): void
    {
        $this->actingAs($this->regularUser())
            ->get(route('admin.users'))
            ->assertRedirect();
    }

    /** @test */
    public function test_guest_cannot_view_users_page(): void
    {
        $this->get(route('admin.users'))
            ->assertRedirect(route('login'));
    }

    // ─────────────────────────────────────────────
    // updateRole
    // ─────────────────────────────────────────────

    /** @test */
    public function test_admin_can_change_a_users_role(): void
    {
        $admin  = $this->admin();
        $target = $this->regularUser();

        $this->actingAs($admin)
            ->patch(route('admin.users.update-role', $target->id), ['role' => 'support'])
            ->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id'   => $target->id,
            'role' => 'support',
        ]);
    }

    /** @test */
    public function test_admin_can_promote_user_to_admin(): void
    {
        $admin  = $this->admin();
        $target = $this->regularUser();

        $this->actingAs($admin)
            ->patch(route('admin.users.update-role', $target->id), ['role' => 'admin'])
            ->assertRedirect();

        $this->assertDatabaseHas('users', ['id' => $target->id, 'role' => 'admin']);
    }

    /** @test */
    public function test_admin_cannot_change_their_own_role(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->patch(route('admin.users.update-role', $admin->id), ['role' => 'user'])
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertDatabaseHas('users', ['id' => $admin->id, 'role' => 'admin']);
    }

    /** @test */
    public function test_update_role_validates_role_value(): void
    {
        $admin  = $this->admin();
        $target = $this->regularUser();

        $this->actingAs($admin)
            ->patch(route('admin.users.update-role', $target->id), ['role' => 'superuser'])
            ->assertSessionHasErrors(['role']);
    }

    /** @test */
    public function test_non_admin_cannot_change_roles(): void
    {
        $user   = $this->regularUser();
        $target = $this->regularUser();

        $this->actingAs($user)
            ->patch(route('admin.users.update-role', $target->id), ['role' => 'admin'])
            ->assertRedirect();

        $this->assertDatabaseHas('users', ['id' => $target->id, 'role' => 'user']);
    }

    // ─────────────────────────────────────────────
    // destroy (delete user)
    // ─────────────────────────────────────────────

    /** @test */
    public function test_admin_can_delete_a_user(): void
    {
        $admin  = $this->admin();
        $target = $this->regularUser();

        $this->actingAs($admin)
            ->delete(route('admin.users.destroy', $target->id))
            ->assertRedirect();

        $this->assertDatabaseMissing('users', ['id' => $target->id]);
    }

    /** @test */
    public function test_admin_cannot_delete_their_own_account(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->delete(route('admin.users.destroy', $admin->id))
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    /** @test */
    public function test_non_admin_cannot_delete_users(): void
    {
        $user   = $this->regularUser();
        $target = $this->regularUser();

        $this->actingAs($user)
            ->delete(route('admin.users.destroy', $target->id))
            ->assertRedirect();

        $this->assertDatabaseHas('users', ['id' => $target->id]);
    }

    /** @test */
    public function test_guest_cannot_delete_users(): void
    {
        $target = $this->regularUser();

        $this->delete(route('admin.users.destroy', $target->id))
            ->assertRedirect(route('login'));

        $this->assertDatabaseHas('users', ['id' => $target->id]);
    }
}
