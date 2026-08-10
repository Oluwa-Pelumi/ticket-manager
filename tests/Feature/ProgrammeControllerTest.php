<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Programme;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProgrammeControllerTest extends TestCase
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

    // ─────────────────────────────────────────────
    // index
    // ─────────────────────────────────────────────

    /** @test */
    public function test_admin_can_view_programmes_index(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.programmes.index'))
            ->assertOk()
            ->assertViewIs('admin.programmes')
            ->assertViewHas('programmes');
    }

    /** @test */
    public function test_non_admin_cannot_access_programmes_index(): void
    {
        $this->actingAs($this->regularUser())
            ->get(route('admin.programmes.index'))
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('error');
    }

    /** @test */
    public function test_guest_is_redirected_from_programmes_index(): void
    {
        $this->get(route('admin.programmes.index'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function test_edit_query_param_passes_editing_programme_to_view(): void
    {
        $programme = Programme::factory()->create();

        $response = $this->actingAs($this->admin())
            ->get(route('admin.programmes.index', ['edit' => $programme->id]))
            ->assertOk();

        $this->assertEquals($programme->id, $response->viewData('editingProgramme')?->id);
    }

    /** @test */
    public function test_programmes_are_ordered_by_name(): void
    {
        Programme::factory()->create(['name' => 'Zoology',          'slug' => 'zoology']);
        Programme::factory()->create(['name' => 'Dentistry', 'slug' => 'computer-science']);

        $response = $this->actingAs($this->admin())
            ->get(route('admin.programmes.index'))
            ->assertOk();

        $programmes = $response->viewData('programmes');
        $this->assertEquals('Dentistry', $programmes->first()->name);
    }

    // ─────────────────────────────────────────────
    // store
    // ─────────────────────────────────────────────

    /** @test */
    public function test_admin_can_create_a_programme(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.programmes.store'), ['name' => 'Medicine'])
            ->assertRedirect(route('admin.programmes.index'));

        $this->assertDatabaseHas('programmes', [
            'name' => 'Medicine',
            'slug' => 'medicine',
        ]);
    }

    /** @test */
    public function test_programme_store_validates_name_is_required(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.programmes.store'), [])
            ->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function test_programme_store_validates_name_is_unique(): void
    {
        Programme::factory()->create(['name' => 'Law', 'slug' => 'law']);

        $this->actingAs($this->admin())
            ->post(route('admin.programmes.store'), ['name' => 'Law'])
            ->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function test_non_admin_cannot_create_programme(): void
    {
        $this->actingAs($this->regularUser())
            ->post(route('admin.programmes.store'), ['name' => 'Should Fail'])
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('error');

        $this->assertDatabaseMissing('programmes', ['name' => 'Should Fail']);
    }

    // ─────────────────────────────────────────────
    // update
    // ─────────────────────────────────────────────

    /** @test */
    public function test_admin_can_update_a_programme(): void
    {
        $programme = Programme::factory()->create(['name' => 'Old Name', 'slug' => 'old-name']);

        $this->actingAs($this->admin())
            ->patch(route('admin.programmes.update', $programme), ['name' => 'New Name'])
            ->assertRedirect(route('admin.programmes.index'));

        $this->assertDatabaseHas('programmes', [
            'id'   => $programme->id,
            'name' => 'New Name',
            'slug' => 'new-name',
        ]);
    }

    /** @test */
    public function test_programme_update_validates_name_is_required(): void
    {
        $programme = Programme::factory()->create();

        $this->actingAs($this->admin())
            ->patch(route('admin.programmes.update', $programme), [])
            ->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function test_programme_update_allows_keeping_same_name(): void
    {
        $programme = Programme::factory()->create(['name' => 'Same Name', 'slug' => 'same-name']);

        $this->actingAs($this->admin())
            ->patch(route('admin.programmes.update', $programme), ['name' => 'Same Name'])
            ->assertRedirect(route('admin.programmes.index'))
            ->assertSessionHasNoErrors();
    }

    /** @test */
    public function test_non_admin_cannot_update_programme(): void
    {
        $programme = Programme::factory()->create(['name' => 'Original', 'slug' => 'original']);

        $this->actingAs($this->regularUser())
            ->patch(route('admin.programmes.update', $programme), ['name' => 'Changed'])
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('error');

        $this->assertDatabaseMissing('programmes', ['name' => 'Changed']);
    }

    // ─────────────────────────────────────────────
    // destroy
    // ─────────────────────────────────────────────

    /** @test */
    public function test_admin_can_delete_a_programme(): void
    {
        $programme = Programme::factory()->create();

        $this->actingAs($this->admin())
            ->delete(route('admin.programmes.destroy', $programme))
            ->assertRedirect(route('admin.programmes.index'));

        $this->assertDatabaseMissing('programmes', ['id' => $programme->id]);
    }

    /** @test */
    public function test_non_admin_cannot_delete_programme(): void
    {
        $programme = Programme::factory()->create();

        $this->actingAs($this->regularUser())
            ->delete(route('admin.programmes.destroy', $programme))
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('programmes', ['id' => $programme->id]);
    }
}
