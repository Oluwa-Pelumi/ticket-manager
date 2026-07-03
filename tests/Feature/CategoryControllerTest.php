<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryControllerTest extends TestCase
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
    public function test_admin_can_view_categories_index(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.categories.index'))
            ->assertOk()
            ->assertViewIs('admin.categories');
    }

    /** @test */
    public function test_non_admin_cannot_access_categories_index(): void
    {
        $this->actingAs($this->regularUser())
            ->get(route('admin.categories.index'))
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('error');
    }

    /** @test */
    public function test_guest_is_redirected_from_categories_index(): void
    {
        $this->get(route('admin.categories.index'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function test_index_passes_editing_category_when_edit_param_present(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin())
            ->get(route('admin.categories.index', ['edit' => $category->id]))
            ->assertOk();

        $this->assertEquals($category->id, $response->viewData('editingCategory')?->id);
    }

    // ─────────────────────────────────────────────
    // store
    // ─────────────────────────────────────────────

    /** @test */
    public function test_admin_can_create_a_category(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.categories.store'), ['name' => 'Billing'])
            ->assertRedirect();

        $this->assertDatabaseHas('categories', [
            'name' => 'Billing',
            'slug' => 'billing',
        ]);
    }

    /** @test */
    public function test_category_store_validates_name_is_required(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.categories.store'), [])
            ->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function test_category_store_slugifies_the_name(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.categories.store'), ['name' => 'Drug Refill Orders'])
            ->assertRedirect();

        $this->assertDatabaseHas('categories', ['slug' => 'drug-refill-orders']);
    }

    /** @test */
    public function test_non_admin_cannot_create_a_category(): void
    {
        $this->actingAs($this->regularUser())
            ->post(route('admin.categories.store'), ['name' => 'Sneaky'])
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('error');
    }

    // ─────────────────────────────────────────────
    // update
    // ─────────────────────────────────────────────

    /** @test */
    public function test_admin_can_update_a_category(): void
    {
        $category = Category::factory()->create(['name' => 'Old Name', 'slug' => 'old-name']);

        $this->actingAs($this->admin())
            ->patch(route('admin.categories.update', $category), ['name' => 'New Name'])
            ->assertRedirect(route('admin.categories.index'));

        $this->assertDatabaseHas('categories', [
            'id'   => $category->id,
            'name' => 'New Name',
            'slug' => 'new-name',
        ]);
    }

    /** @test */
    public function test_category_update_validates_name(): void
    {
        $category = Category::factory()->create();

        $this->actingAs($this->admin())
            ->patch(route('admin.categories.update', $category), [])
            ->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function test_non_admin_cannot_update_a_category(): void
    {
        $category = Category::factory()->create();

        $this->actingAs($this->regularUser())
            ->patch(route('admin.categories.update', $category), ['name' => 'Changed'])
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('error');
    }

    // ─────────────────────────────────────────────
    // destroy
    // ─────────────────────────────────────────────

    /** @test */
    public function test_admin_can_delete_a_category(): void
    {
        $category = Category::factory()->create();

        $this->actingAs($this->admin())
            ->delete(route('admin.categories.destroy', $category))
            ->assertRedirect(route('admin.categories.index'));

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    /** @test */
    public function test_non_admin_cannot_delete_a_category(): void
    {
        $category = Category::factory()->create();

        $this->actingAs($this->regularUser())
            ->delete(route('admin.categories.destroy', $category))
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('error');
    }
}
