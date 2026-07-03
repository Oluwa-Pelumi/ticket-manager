<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Faq;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FaqControllerTest extends TestCase
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
    public function test_admin_can_view_faqs_index(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.faqs.index'))
            ->assertOk()
            ->assertViewIs('admin.faqs');
    }

    /** @test */
    public function test_non_admin_cannot_access_faqs_index(): void
    {
        $this->actingAs($this->regularUser())
            ->get(route('admin.faqs.index'))
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('error');
    }

    /** @test */
    public function test_guest_is_redirected_from_faqs_index(): void
    {
        $this->get(route('admin.faqs.index'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function test_faqs_are_ordered_by_order_column(): void
    {
        Faq::factory()->create(['question' => 'Second', 'order' => 2]);
        Faq::factory()->create(['question' => 'First',  'order' => 1]);

        $response = $this->actingAs($this->admin())
            ->get(route('admin.faqs.index'))
            ->assertOk();

        $faqs = $response->viewData('faqs');
        $this->assertEquals('First', $faqs->first()->question);
    }

    // ─────────────────────────────────────────────
    // store
    // ─────────────────────────────────────────────

    /** @test */
    public function test_admin_can_create_a_faq(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.faqs.store'), [
                'question' => 'How do I track my order?',
                'answer'   => 'Use the tracking page.',
                'order'    => 1,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('faqs', [
            'question' => 'How do I track my order?',
            'answer'   => 'Use the tracking page.',
        ]);
    }

    /** @test */
    public function test_faq_store_validates_required_fields(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.faqs.store'), [])
            ->assertSessionHasErrors(['question', 'answer']);
    }

    /** @test */
    public function test_faq_store_order_is_optional(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.faqs.store'), [
                'question' => 'Optional order test?',
                'answer'   => 'Yes, it is optional.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('faqs', ['question' => 'Optional order test?']);
    }

    /** @test */
    public function test_non_admin_cannot_create_a_faq(): void
    {
        $this->actingAs($this->regularUser())
            ->post(route('admin.faqs.store'), [
                'question' => 'Test?',
                'answer'   => 'No.',
            ])
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('error');
    }

    // ─────────────────────────────────────────────
    // update
    // ─────────────────────────────────────────────

    /** @test */
    public function test_admin_can_update_a_faq(): void
    {
        $faq = Faq::factory()->create(['question' => 'Old Q', 'answer' => 'Old A']);

        $this->actingAs($this->admin())
            ->patch(route('admin.faqs.update', $faq), [
                'question' => 'New Q',
                'answer'   => 'New A',
                'order'    => 5,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('faqs', [
            'id'       => $faq->id,
            'question' => 'New Q',
            'answer'   => 'New A',
            'order'    => 5,
        ]);
    }

    /** @test */
    public function test_faq_update_validates_required_fields(): void
    {
        $faq = Faq::factory()->create();

        $this->actingAs($this->admin())
            ->patch(route('admin.faqs.update', $faq), [])
            ->assertSessionHasErrors(['question', 'answer']);
    }

    /** @test */
    public function test_non_admin_cannot_update_a_faq(): void
    {
        $faq = Faq::factory()->create();

        $this->actingAs($this->regularUser())
            ->patch(route('admin.faqs.update', $faq), [
                'question' => 'Hacked?',
                'answer'   => 'No.',
            ])
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('error');
    }

    // ─────────────────────────────────────────────
    // destroy
    // ─────────────────────────────────────────────

    /** @test */
    public function test_admin_can_delete_a_faq(): void
    {
        $faq = Faq::factory()->create();

        $this->actingAs($this->admin())
            ->delete(route('admin.faqs.destroy', $faq))
            ->assertRedirect();

        $this->assertDatabaseMissing('faqs', ['id' => $faq->id]);
    }

    /** @test */
    public function test_non_admin_cannot_delete_a_faq(): void
    {
        $faq = Faq::factory()->create();

        $this->actingAs($this->regularUser())
            ->delete(route('admin.faqs.destroy', $faq))
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('error');
    }
}
