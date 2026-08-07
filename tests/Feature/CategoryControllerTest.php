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
    public function test_admin_can_view_categories_page(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.categories.index'))
            ->assertOk()
            ->assertViewIs('admin.categories')
            ->assertViewHas('categories');
    }

    /** @test */
    public function test_non_admin_cannot_view_categories_page(): void
    {
        $this->actingAs($this->regularUser())
            ->get(route('admin.categories.index'))
            ->assertRedirect();
    }

    /** @test */
    public function test_guest_cannot_view_categories_page(): void
    {
        $this->get(route('admin.categories.index'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function test_edit_query_param_passes_editing_category_to_view(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin())
            ->get(route('admin.categories.index', ['edit' => $category->id]))
            ->assertOk();

        $editingCategory = $response->viewData('editingCategory');
        $this->assertNotNull($editingCategory);
        $this->assertEquals($category->id, $editingCategory->id);
    }

    /** @test */
    public function test_invalid_edit_id_passes_null_to_view(): void
    {
        $response = $this->actingAs($this->admin())
            ->get(route('admin.categories.index', ['edit' => 99999]))
            ->assertOk();

        $this->assertNull($response->viewData('editingCategory'));
    }

    // ─────────────────────────────────────────────
    // store
    // ─────────────────────────────────────────────

    /** @test */
    public function test_admin_can_create_a_category(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.categories.store'), ['name' => 'Prescription Issues'])
            ->assertRedirect();

        $this->assertDatabaseHas('categories', [
            'name' => 'Prescription Issues',
            'slug' => 'prescription-issues',
        ]);
    }

    /** @test */
    public function test_slug_is_auto_generated_from_name(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.categories.store'), ['name' => 'Order & Delivery']);

        $this->assertDatabaseHas('categories', ['slug' => 'order-delivery']);
    }

    /** @test */
    public function test_category_store_requires_name(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.categories.store'), [])
            ->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function test_non_admin_cannot_create_category(): void
    {
        $this->actingAs($this->regularUser())
            ->post(route('admin.categories.store'), ['name' => 'Should Fail'])
            ->assertRedirect();

        $this->assertDatabaseMissing('categories', ['name' => 'Should Fail']);
    }

    // ─────────────────────────────────────────────
    // update
    // ─────────────────────────────────────────────

    /** @test */
    public function test_admin_can_update_a_category(): void
    {
        $category = Category::factory()->create(['name' => 'Old Name']);

        $this->actingAs($this->admin())
            ->patch(route('admin.categories.update', $category->id), ['name' => 'New Name'])
            ->assertRedirect(route('admin.categories.index'));

        $this->assertDatabaseHas('categories', [
            'id'   => $category->id,
            'name' => 'New Name',
            'slug' => 'new-name',
        ]);
    }

    /** @test */
    public function test_category_update_requires_name(): void
    {
        $category = Category::factory()->create();

        $this->actingAs($this->admin())
            ->patch(route('admin.categories.update', $category->id), [])
            ->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function test_non_admin_cannot_update_category(): void
    {
        $category = Category::factory()->create(['name' => 'Original']);

        $this->actingAs($this->regularUser())
            ->patch(route('admin.categories.update', $category->id), ['name' => 'Changed'])
            ->assertRedirect();

        $this->assertDatabaseMissing('categories', ['name' => 'Changed']);
    }

    // ─────────────────────────────────────────────
    // destroy
    // ─────────────────────────────────────────────

    /** @test */
    public function test_admin_can_delete_a_category(): void
    {
        $category = Category::factory()->create();

        $this->actingAs($this->admin())
            ->delete(route('admin.categories.destroy', $category->id))
            ->assertRedirect(route('admin.categories.index'));

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    /** @test */
    public function test_non_admin_cannot_delete_category(): void
    {
        $category = Category::factory()->create();

        $this->actingAs($this->regularUser())
            ->delete(route('admin.categories.destroy', $category->id))
            ->assertRedirect();

        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }
}
