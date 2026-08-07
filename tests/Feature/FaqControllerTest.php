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
    public function test_admin_can_view_faqs_page(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.faqs.index'))
            ->assertOk()
            ->assertViewIs('admin.faqs')
            ->assertViewHas('faqs');
    }

    /** @test */
    public function test_faqs_are_ordered_by_order_column(): void
    {
        Faq::factory()->create(['order' => 3, 'question' => 'Third']);
        Faq::factory()->create(['order' => 1, 'question' => 'First']);
        Faq::factory()->create(['order' => 2, 'question' => 'Second']);

        $response = $this->actingAs($this->admin())
            ->get(route('admin.faqs.index'))
            ->assertOk();

        $faqs = $response->viewData('faqs');
        $this->assertEquals('First',  $faqs[0]->question);
        $this->assertEquals('Second', $faqs[1]->question);
        $this->assertEquals('Third',  $faqs[2]->question);
    }

    /** @test */
    public function test_edit_query_param_passes_editing_faq_to_view(): void
    {
        $faq = Faq::factory()->create();

        $response = $this->actingAs($this->admin())
            ->get(route('admin.faqs.index', ['edit' => $faq->id]))
            ->assertOk();

        $editingFaq = $response->viewData('editingFaq');
        $this->assertNotNull($editingFaq);
        $this->assertEquals($faq->id, $editingFaq->id);
    }

    /** @test */
    public function test_no_edit_param_passes_null_editing_faq(): void
    {
        $response = $this->actingAs($this->admin())
            ->get(route('admin.faqs.index'))
            ->assertOk();

        $this->assertNull($response->viewData('editingFaq'));
    }

    /** @test */
    public function test_non_admin_cannot_view_faqs_page(): void
    {
        $this->actingAs($this->regularUser())
            ->get(route('admin.faqs.index'))
            ->assertRedirect();
    }

    /** @test */
    public function test_guest_cannot_view_faqs_page(): void
    {
        $this->get(route('admin.faqs.index'))
            ->assertRedirect(route('login'));
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
                'answer'   => 'Use your reference code on the check-status page.',
                'order'    => 1,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('faqs', [
            'question' => 'How do I track my order?',
            'order'    => 1,
        ]);
    }

    /** @test */
    public function test_faq_store_requires_question_and_answer(): void
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
                'question' => 'Can I cancel an order?',
                'answer'   => 'Contact support within 24 hours.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('faqs', ['question' => 'Can I cancel an order?']);
    }

    /** @test */
    public function test_non_admin_cannot_create_faq(): void
    {
        $this->actingAs($this->regularUser())
            ->post(route('admin.faqs.store'), [
                'question' => 'Should fail',
                'answer'   => 'Definitely',
            ])
            ->assertRedirect();

        $this->assertDatabaseMissing('faqs', ['question' => 'Should fail']);
    }

    // ─────────────────────────────────────────────
    // update
    // ─────────────────────────────────────────────

    /** @test */
    public function test_admin_can_update_a_faq(): void
    {
        $faq = Faq::factory()->create(['question' => 'Old Q', 'answer' => 'Old A']);

        $this->actingAs($this->admin())
            ->patch(route('admin.faqs.update', $faq->id), [
                'question' => 'Updated Q',
                'answer'   => 'Updated A',
                'order'    => 5,
            ])
            ->assertRedirect(route('admin.faqs.index'));

        $this->assertDatabaseHas('faqs', [
            'id'       => $faq->id,
            'question' => 'Updated Q',
            'answer'   => 'Updated A',
            'order'    => 5,
        ]);
    }

    /** @test */
    public function test_faq_update_requires_question_and_answer(): void
    {
        $faq = Faq::factory()->create();

        $this->actingAs($this->admin())
            ->patch(route('admin.faqs.update', $faq->id), [])
            ->assertSessionHasErrors(['question', 'answer']);
    }

    /** @test */
    public function test_non_admin_cannot_update_faq(): void
    {
        $faq = Faq::factory()->create(['question' => 'Original']);

        $this->actingAs($this->regularUser())
            ->patch(route('admin.faqs.update', $faq->id), [
                'question' => 'Changed',
                'answer'   => 'Changed answer',
            ])
            ->assertRedirect();

        $this->assertDatabaseMissing('faqs', ['question' => 'Changed']);
    }

    // ─────────────────────────────────────────────
    // destroy
    // ─────────────────────────────────────────────

    /** @test */
    public function test_admin_can_delete_a_faq(): void
    {
        $faq = Faq::factory()->create();

        $this->actingAs($this->admin())
            ->delete(route('admin.faqs.destroy', $faq->id))
            ->assertRedirect();

        $this->assertDatabaseMissing('faqs', ['id' => $faq->id]);
    }

    /** @test */
    public function test_non_admin_cannot_delete_faq(): void
    {
        $faq = Faq::factory()->create();

        $this->actingAs($this->regularUser())
            ->delete(route('admin.faqs.destroy', $faq->id))
            ->assertRedirect();

        $this->assertDatabaseHas('faqs', ['id' => $faq->id]);
    }
}
