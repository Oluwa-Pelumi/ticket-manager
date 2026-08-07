<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Faq;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Tests for public pages, auth-agnostic routes, and polling endpoints.
 */
class GeneralRoutesTest extends TestCase
{
    use RefreshDatabase;

    // ─────────────────────────────────────────────
    // Root redirect
    // ─────────────────────────────────────────────

    /** @test */
    public function test_root_redirects_to_home(): void
    {
        $this->get('/')->assertRedirect(route('home'));
    }

    // ─────────────────────────────────────────────
    // Home page
    // ─────────────────────────────────────────────

    /** @test */
    public function test_home_page_is_accessible_by_guests(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertViewIs('home')
            ->assertViewHas('stats')
            ->assertViewHas('faqs');
    }

    /** @test */
    public function test_home_page_stats_reflect_ticket_counts(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        Ticket::factory()->count(2)->create(['user_id' => $user->id, 'status' => 'open']);
        Ticket::factory()->count(1)->create(['user_id' => $user->id, 'status' => 'closed']);
        Ticket::factory()->count(1)->create(['user_id' => $user->id, 'status' => 'in-progress']);

        $response = $this->get(route('home'))->assertOk();
        $stats    = $response->viewData('stats');

        $this->assertEquals(4, $stats['totalTickets']);
        $this->assertEquals(2, $stats['openTickets']);
        $this->assertEquals(1, $stats['resolvedTickets']);
        $this->assertEquals(1, $stats['inProgressTickets']);
    }

    /** @test */
    public function test_home_page_shows_faqs_ordered(): void
    {
        Faq::factory()->create(['order' => 2, 'question' => 'B question?']);
        Faq::factory()->create(['order' => 1, 'question' => 'A question?']);

        $response = $this->get(route('home'))->assertOk();
        $faqs     = $response->viewData('faqs');

        $this->assertEquals('A question?', $faqs[0]->question);
        $this->assertEquals('B question?', $faqs[1]->question);
    }

    // ─────────────────────────────────────────────
    // Submit ticket page (auth required on this branch)
    // ─────────────────────────────────────────────

    /** @test */
    public function test_submit_ticket_page_requires_authentication(): void
    {
        $this->get(route('submit-ticket'))->assertRedirect(route('login'));
    }

    /** @test */
    public function test_submit_ticket_page_is_accessible_when_authenticated(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        Category::factory()->count(2)->create();

        $response = $this->actingAs($user)->get(route('submit-ticket'))->assertOk();
        $this->assertCount(2, $response->viewData('categories'));
    }

    // ─────────────────────────────────────────────
    // Check status page
    // ─────────────────────────────────────────────

    /** @test */
    public function test_check_status_page_is_accessible_by_guests(): void
    {
        $this->get(route('check-status'))->assertOk()->assertViewIs('check-status');
    }

    /** @test */
    public function test_check_status_page_passes_categories_to_view(): void
    {
        Category::factory()->count(3)->create();
        $response = $this->get(route('check-status'))->assertOk();
        $this->assertCount(3, $response->viewData('categories'));
    }

    // ─────────────────────────────────────────────
    // Ticket statuses polling endpoint
    // ─────────────────────────────────────────────

    /** @test */
    public function test_ticket_statuses_requires_authentication(): void
    {
        $this->getJson(route('tickets.statuses'))->assertUnauthorized();
    }

    /** @test */
    public function test_admin_gets_all_ticket_statuses(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user  = User::factory()->create(['role' => 'user']);
        Ticket::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->actingAs($admin)->getJson(route('tickets.statuses'))->assertOk();

        $data = $response->json();
        $this->assertCount(3, $data);
        $this->assertArrayHasKey('id',     $data[0]);
        $this->assertArrayHasKey('status', $data[0]);
    }

    /** @test */
    public function test_user_gets_only_their_own_ticket_statuses(): void
    {
        $user  = User::factory()->create(['role' => 'user']);
        $other = User::factory()->create(['role' => 'user']);
        $mine  = Ticket::factory()->create(['user_id' => $user->id]);
        Ticket::factory()->create(['user_id' => $other->id]);

        $response = $this->actingAs($user)->getJson(route('tickets.statuses'))->assertOk();

        $ids = collect($response->json())->pluck('id');
        $this->assertTrue($ids->contains($mine->id));
        $this->assertCount(1, $ids);
    }
}
