<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Category;
use App\Models\Comment;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TicketControllerTest extends TestCase
{
    use RefreshDatabase;

    // ─────────────────────────────────────────────
    // Helpers
    // ─────────────────────────────────────────────

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

    private function makeTicket(array $overrides = []): Ticket
    {
        $user = $overrides['user'] ?? $this->regularUser();
        unset($overrides['user']);

        return Ticket::factory()->create(array_merge([
            'user_id' => $user->id,
            'status'  => 'open',
        ], $overrides));
    }

    // ─────────────────────────────────────────────
    // index (dashboard)
    // ─────────────────────────────────────────────

    /** @test */
    public function test_guests_cannot_access_dashboard(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
    }

    /** @test */
    public function test_admin_sees_all_tickets_on_dashboard(): void
    {
        $admin = $this->admin();
        Ticket::factory()->count(3)->create();

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertViewIs('dashboard')
            ->assertViewHas('tickets');
    }

    /** @test */
    public function test_support_sees_only_assigned_tickets(): void
    {
        $support  = $this->support();
        $assigned = Ticket::factory()->create(['attended_to_by' => $support->id]);
        $other    = Ticket::factory()->create(['attended_to_by' => null]);

        $response = $this->actingAs($support)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertViewIs('dashboard');

        $tickets = $response->viewData('tickets');
        $this->assertTrue($tickets->contains($assigned));
        $this->assertFalse($tickets->contains($other));
    }

    /** @test */
    public function test_user_sees_only_their_own_tickets(): void
    {
        $user  = $this->regularUser();
        $mine  = Ticket::factory()->create(['user_id' => $user->id]);
        $other = Ticket::factory()->create();

        $response = $this->actingAs($user)->get(route('dashboard'))->assertOk();

        $tickets = $response->viewData('tickets');
        $this->assertTrue($tickets->contains($mine));
        $this->assertFalse($tickets->contains($other));
    }

    // ─────────────────────────────────────────────
    // save (create ticket)
    // ─────────────────────────────────────────────

    /** @test */
    public function test_guest_can_submit_a_ticket(): void
    {
        Notification::fake();
        $category = Category::factory()->create(['slug' => 'general']);

        $this->post(route('save-ticket'), [
            'name'        => 'John Guest',
            'email'       => 'guest@example.com',
            'subject'     => 'general',
            'content'     => 'I need help with my order.',
            'priority'    => 'medium',
            'category_id' => $category->id,
        ])->assertRedirect();

        $this->assertDatabaseHas('tickets', ['email' => 'guest@example.com']);
    }

    /** @test */
    public function test_authenticated_user_can_submit_a_ticket(): void
    {
        Notification::fake();
        $user     = $this->regularUser();
        $category = Category::factory()->create(['slug' => 'general']);

        $this->actingAs($user)->post(route('save-ticket'), [
            'name'        => $user->name,
            'email'       => $user->email,
            'subject'     => 'general',
            'content'     => 'Please assist.',
            'priority'    => 'low',
            'category_id' => $category->id,
        ])->assertRedirect();

        $this->assertDatabaseHas('tickets', [
            'user_id' => $user->id,
            'email'   => $user->email,
        ]);
    }

    /** @test */
    public function test_ticket_save_validates_required_fields(): void
    {
        $this->post(route('save-ticket'), [])
            ->assertSessionHasErrors(['name', 'email', 'subject', 'content', 'priority']);
    }

    /** @test */
    public function test_ticket_save_rejects_invalid_priority(): void
    {
        $this->post(route('save-ticket'), [
            'name'     => 'Test',
            'email'    => 'test@test.com',
            'subject'  => 'general',
            'content'  => 'Help',
            'priority' => 'super-urgent',
        ])->assertSessionHasErrors(['priority']);
    }

    /** @test */
    public function test_ticket_save_with_attachments_stores_files(): void
    {
        Notification::fake();
        Storage::fake('public');
        $user     = $this->regularUser();
        $category = Category::factory()->create(['slug' => 'general']);

        $this->actingAs($user)->post(route('save-ticket'), [
            'name'        => $user->name,
            'email'       => $user->email,
            'subject'     => 'general',
            'content'     => 'Issue with attachments',
            'priority'    => 'high',
            'category_id' => $category->id,
            'attachments' => [UploadedFile::fake()->image('screenshot.png')],
        ])->assertRedirect();

        $ticket = Ticket::where('user_id', $user->id)->first();
        $this->assertNotEmpty($ticket->attachments);
        Storage::disk('public')->assertExists($ticket->attachments[0]);
    }

    /** @test */
    public function test_whatsapp_number_is_synced_to_user_profile_on_ticket_save(): void
    {
        Notification::fake();
        $user     = $this->regularUser();
        $category = Category::factory()->create(['slug' => 'general']);

        $this->assertNull($user->whatsapp_number);

        $this->actingAs($user)->post(route('save-ticket'), [
            'name'            => $user->name,
            'email'           => $user->email,
            'subject'         => 'general',
            'content'         => 'Need help.',
            'priority'        => 'medium',
            'category_id'     => $category->id,
            'whatsapp_number' => '+2348012345678',
        ])->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id'              => $user->id,
            'whatsapp_number' => '+2348012345678',
        ]);
    }

    // ─────────────────────────────────────────────
    // show
    // ─────────────────────────────────────────────

    /** @test */
    public function test_anyone_can_view_a_ticket_by_hashid(): void
    {
        $ticket = $this->makeTicket();

        $this->get(route('ticket.show', $ticket->hashid))
            ->assertOk()
            ->assertViewIs('ticket.show')
            ->assertViewHas('ticket');
    }

    // ─────────────────────────────────────────────
    // update
    // ─────────────────────────────────────────────

    /** @test */
    public function test_ticket_owner_can_update_their_ticket(): void
    {
        $user   = $this->regularUser();
        $ticket = $this->makeTicket(['user' => $user]);

        $this->actingAs($user)->patch(route('update-ticket', $ticket->hashid), [
            'subject'  => 'Updated subject',
            'content'  => 'Updated content',
            'priority' => 'high',
        ])->assertRedirect();

        $this->assertDatabaseHas('tickets', [
            'id'      => $ticket->id,
            'subject' => 'Updated subject',
        ]);
    }

    /** @test */
    public function test_non_owner_cannot_update_another_users_ticket(): void
    {
        $owner  = $this->regularUser();
        $other  = $this->regularUser();
        $ticket = $this->makeTicket(['user' => $owner]);

        $this->actingAs($other)->patch(route('update-ticket', $ticket->hashid), [
            'subject'  => 'Hacked',
            'content'  => 'Hacked content',
            'priority' => 'low',
        ])->assertSessionHas('error');
    }

    /** @test */
    public function test_closed_ticket_cannot_be_updated(): void
    {
        $user   = $this->regularUser();
        $ticket = $this->makeTicket(['user' => $user, 'status' => 'closed']);

        $this->actingAs($user)->patch(route('update-ticket', $ticket->hashid), [
            'subject'  => 'Trying again',
            'content'  => 'Still need help',
            'priority' => 'medium',
        ])->assertSessionHas('error');
    }

    /** @test */
    public function test_ticket_update_with_file_attachments(): void
    {
        Storage::fake('public');
        $user   = $this->regularUser();
        $ticket = $this->makeTicket(['user' => $user]);

        $this->actingAs($user)
            ->patch(route('update-ticket', $ticket->hashid), [
                'subject'     => $ticket->subject,
                'content'     => $ticket->content,
                'priority'    => 'medium',
                'attachments' => [UploadedFile::fake()->image('update.png')],
            ])
            ->assertRedirect();

        $ticket->refresh();
        $this->assertNotEmpty($ticket->attachments);
        Storage::disk('public')->assertExists($ticket->attachments[0]);
    }

    /** @test */
    public function test_ticket_update_retains_existing_attachments(): void
    {
        Storage::fake('public');
        $user   = $this->regularUser();
        $ticket = $this->makeTicket(['user' => $user]);

        Storage::disk('public')->put('tickets/existing.png', 'content');
        $ticket->update(['attachments' => ['tickets/existing.png']]);

        $this->actingAs($user)
            ->patch(route('update-ticket', $ticket->hashid), [
                'subject'              => $ticket->subject,
                'content'              => $ticket->content,
                'priority'             => 'medium',
                'existing_attachments' => ['tickets/existing.png'],
            ])
            ->assertRedirect();

        $ticket->refresh();
        $this->assertContains('tickets/existing.png', $ticket->attachments);
    }

    // ─────────────────────────────────────────────
    // updateStatus (legacy PATCH route)
    // ─────────────────────────────────────────────

    /** @test */
    public function test_admin_can_update_ticket_status(): void
    {
        $admin  = $this->admin();
        $ticket = $this->makeTicket();

        $this->actingAs($admin)->patch(route('update-ticket-status'), [
            'id'     => $ticket->id,
            'status' => 'in-progress',
        ])->assertRedirect();

        $this->assertDatabaseHas('tickets', ['id' => $ticket->id, 'status' => 'in-progress']);
    }

    /** @test */
    public function test_regular_user_cannot_update_ticket_status(): void
    {
        $user   = $this->regularUser();
        $ticket = $this->makeTicket();

        $this->actingAs($user)->patch(route('update-ticket-status'), [
            'id'     => $ticket->id,
            'status' => 'closed',
        ])->assertSessionHas('error');
    }

    /** @test */
    public function test_ticket_reassigns_when_reopened_from_closed(): void
    {
        $admin            = $this->admin();
        $pastSupport      = $this->support();
        $availableSupport = User::factory()->create(['role' => 'support']);

        Ticket::factory()->create(['status' => 'open', 'attended_to_by' => [$pastSupport->id]]);

        $ticket = Ticket::factory()->create([
            'status'         => 'closed',
            'attended_to_by' => [$pastSupport->id],
        ]);

        $this->actingAs($admin)
            ->patch(route('update-ticket-status'), ['id' => $ticket->id, 'status' => 'open'])
            ->assertRedirect();

        $ticket->refresh();
        $this->assertEquals('open', $ticket->status);
        $this->assertContains($availableSupport->id, $ticket->attended_to_by);
        $this->assertEquals($availableSupport->id, end($ticket->attended_to_by));
    }

    // ─────────────────────────────────────────────
    // RESTful updateTicketStatus
    // ─────────────────────────────────────────────

    /** @test */
    public function test_admin_can_patch_ticket_status_via_rest(): void
    {
        $admin  = $this->admin();
        $ticket = $this->makeTicket();

        $this->actingAs($admin)
            ->patch(route('tickets.update-status', [$ticket->hashid, 'closed']))
            ->assertJson(['success' => true, 'status' => 'closed']);

        $this->assertDatabaseHas('tickets', ['id' => $ticket->id, 'status' => 'closed']);
    }

    /** @test */
    public function test_invalid_status_returns_422(): void
    {
        $admin  = $this->admin();
        $ticket = $this->makeTicket();

        $this->actingAs($admin)
            ->patch(route('tickets.update-status', [$ticket->hashid, 'unknown']))
            ->assertJson(['error' => 'Invalid status']);
    }

    /** @test */
    public function test_regular_user_gets_403_on_rest_status_update(): void
    {
        $user   = $this->regularUser();
        $ticket = $this->makeTicket();

        $this->actingAs($user)
            ->patch(route('tickets.update-status', [$ticket->hashid, 'closed']))
            ->assertJson(['error' => 'Unauthorized']);
    }

    // ─────────────────────────────────────────────
    // ticketStatuses polling
    // ─────────────────────────────────────────────

    /** @test */
    public function test_ticket_statuses_endpoint_returns_json(): void
    {
        $admin = $this->admin();
        Ticket::factory()->count(2)->create();

        $this->actingAs($admin)
            ->getJson(route('tickets.statuses'))
            ->assertOk()
            ->assertJsonStructure([['id', 'status']]);
    }

    // ─────────────────────────────────────────────
    // destroyTicket (DELETE /tickets/{ticket})
    // ─────────────────────────────────────────────

    /** @test */
    public function test_admin_can_delete_a_ticket_via_rest(): void
    {
        $admin  = $this->admin();
        $ticket = $this->makeTicket();

        $this->actingAs($admin)
            ->delete(route('tickets.destroy', $ticket->hashid))
            ->assertJson(['success' => true]);

        $this->assertDatabaseMissing('tickets', ['id' => $ticket->id]);
    }

    /** @test */
    public function test_non_admin_gets_403_when_deleting_ticket_via_rest(): void
    {
        $user   = $this->regularUser();
        $ticket = $this->makeTicket();

        $this->actingAs($user)
            ->delete(route('tickets.destroy', $ticket->hashid))
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('error');
    }

    // ─────────────────────────────────────────────
    // bulkDelete (legacy)
    // ─────────────────────────────────────────────

    /** @test */
    public function test_admin_can_bulk_delete_tickets(): void
    {
        $admin   = $this->admin();
        $tickets = Ticket::factory()->count(3)->create();
        $ids     = $tickets->pluck('id')->toArray();

        $this->actingAs($admin)->delete(route('bulk-delete-tickets'), ['ids' => $ids])->assertRedirect();

        foreach ($ids as $id) {
            $this->assertDatabaseMissing('tickets', ['id' => $id]);
        }
    }

    /** @test */
    public function test_non_admin_cannot_bulk_delete_tickets(): void
    {
        $user    = $this->regularUser();
        $tickets = Ticket::factory()->count(2)->create();

        $this->actingAs($user)
            ->delete(route('bulk-delete-tickets'), ['ids' => $tickets->pluck('id')->toArray()])
            ->assertSessionHas('error');
    }

    // ─────────────────────────────────────────────
    // bulkDestroyTickets (RESTful)
    // ─────────────────────────────────────────────

    /** @test */
    public function test_admin_can_bulk_destroy_tickets_via_rest(): void
    {
        $admin   = $this->admin();
        $tickets = Ticket::factory()->count(3)->create();
        $ids     = $tickets->pluck('id')->toArray();

        $this->actingAs($admin)
            ->delete(route('tickets.bulk-destroy'), ['ids' => $ids])
            ->assertJson(['success' => true, 'deleted' => 3]);

        foreach ($ids as $id) {
            $this->assertDatabaseMissing('tickets', ['id' => $id]);
        }
    }

    // ─────────────────────────────────────────────
    // bulkUpdateStatus
    // ─────────────────────────────────────────────

    /** @test */
    public function test_admin_can_bulk_update_status_via_legacy_route(): void
    {
        $admin   = $this->admin();
        $tickets = Ticket::factory()->count(2)->create(['status' => 'open']);
        $ids     = $tickets->pluck('id')->toArray();

        $this->actingAs($admin)->patch(route('bulk-update-ticket-status'), [
            'ids'    => $ids,
            'status' => 'closed',
        ])->assertRedirect();

        foreach ($ids as $id) {
            $this->assertDatabaseHas('tickets', ['id' => $id, 'status' => 'closed']);
        }
    }

    /** @test */
    public function test_admin_can_bulk_update_status_via_rest(): void
    {
        $admin   = $this->admin();
        $tickets = Ticket::factory()->count(2)->create(['status' => 'open']);
        $ids     = $tickets->pluck('id')->toArray();

        $this->actingAs($admin)
            ->patch(route('tickets.bulk-status'), ['ids' => $ids, 'status' => 'in-progress'])
            ->assertJson(['success' => true, 'status' => 'in-progress']);
    }

    /** @test */
    public function test_regular_user_cannot_bulk_update_status(): void
    {
        $user    = $this->regularUser();
        $tickets = Ticket::factory()->count(2)->create();

        $this->actingAs($user)
            ->patch(route('tickets.bulk-status'), [
                'ids'    => $tickets->pluck('id')->toArray(),
                'status' => 'closed',
            ])
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('error');
    }

    // ─────────────────────────────────────────────
    // deleteTicket (legacy admin DELETE)
    // ─────────────────────────────────────────────

    /** @test */
    public function test_admin_can_delete_single_ticket_via_legacy_route(): void
    {
        $admin  = $this->admin();
        $ticket = $this->makeTicket();

        $this->actingAs($admin)
            ->delete(route('delete-ticket'), ['id' => $ticket->id])
            ->assertRedirect();

        $this->assertDatabaseMissing('tickets', ['id' => $ticket->id]);
    }

    // ─────────────────────────────────────────────
    // addComment
    // ─────────────────────────────────────────────

    /** @test */
    public function test_authenticated_user_can_add_a_comment(): void
    {
        $user   = $this->regularUser();
        $ticket = $this->makeTicket();

        $this->actingAs($user)
            ->post(route('tickets.add-comment', $ticket->hashid), ['content' => 'This is my comment.'])
            ->assertRedirect();

        $this->assertDatabaseHas('comments', [
            'ticket_id' => $ticket->id,
            'user_id'   => $user->id,
            'content'   => 'This is my comment.',
        ]);
    }

    /** @test */
    public function test_guest_can_add_a_comment_via_public_route(): void
    {
        $ticket = $this->makeTicket();

        $this->post(route('add-comment', $ticket->hashid), ['content' => 'Guest comment here.'])
            ->assertRedirect();

        $this->assertDatabaseHas('comments', [
            'ticket_id' => $ticket->id,
            'content'   => 'Guest comment here.',
        ]);
    }

    /** @test */
    public function test_comment_requires_content(): void
    {
        $user   = $this->regularUser();
        $ticket = $this->makeTicket();

        $this->actingAs($user)
            ->post(route('tickets.add-comment', $ticket->hashid), [])
            ->assertSessionHasErrors(['content']);
    }

    /** @test */
    public function test_staff_comment_auto_transitions_open_ticket_to_in_progress(): void
    {
        Notification::fake();
        $support = $this->support();
        $user    = $this->regularUser();
        $ticket  = $this->makeTicket(['user' => $user, 'status' => 'open']);

        $this->actingAs($support)
            ->post(route('tickets.add-comment', $ticket->hashid), ['content' => 'Support reply.'])
            ->assertRedirect();

        $this->assertDatabaseHas('tickets', ['id' => $ticket->id, 'status' => 'in-progress']);
    }

    /** @test */
    public function test_comment_with_attachments_stores_files(): void
    {
        Notification::fake();
        Storage::fake('public');
        $user   = $this->regularUser();
        $ticket = $this->makeTicket(['user' => $user]);

        $this->actingAs($user)
            ->post(route('tickets.add-comment', $ticket->hashid), [
                'content'     => 'Comment with file.',
                'attachments' => [UploadedFile::fake()->image('proof.png')],
            ])
            ->assertRedirect();

        $comment = $ticket->comments()->latest()->first();
        $this->assertNotEmpty($comment->attachments);
        Storage::disk('public')->assertExists($comment->attachments[0]);
    }

    /** @test */
    public function test_comment_on_closed_ticket_reopens_it(): void
    {
        Notification::fake();
        $user   = $this->regularUser();
        $ticket = $this->makeTicket(['user' => $user, 'status' => 'closed']);

        $this->actingAs($user)
            ->post(route('tickets.add-comment', $ticket->hashid), ['content' => 'I need this reopened.'])
            ->assertRedirect();

        $this->assertDatabaseHas('tickets', ['id' => $ticket->id, 'status' => 'open']);
    }

    /** @test */
    public function test_support_comment_assigns_attendant_to_ticket(): void
    {
        Notification::fake();
        $support = $this->support();
        $ticket  = $this->makeTicket(['status' => 'open', 'attended_to_by' => null]);

        $this->actingAs($support)
            ->post(route('tickets.add-comment', $ticket->hashid), ['content' => 'Support is handling this.'])
            ->assertRedirect();

        $ticket->refresh();
        $this->assertContains($support->id, $ticket->attended_to_by);
    }

    /** @test */
    public function test_past_support_cannot_reply_to_ticket(): void
    {
        $pastSupport    = $this->support();
        $currentSupport = User::factory()->create(['role' => 'support']);

        $ticket = Ticket::factory()->create([
            'status'         => 'open',
            'attended_to_by' => [$pastSupport->id, $currentSupport->id],
        ]);

        $this->actingAs($pastSupport)
            ->post(route('add-comment', ['ticket' => $ticket->id]), [
                'content' => 'Trying to reply as past support',
            ])
            ->assertSessionHas('error', 'You are not the currently assigned support for this ticket and cannot reply.');

        $this->assertDatabaseMissing('comments', ['content' => 'Trying to reply as past support']);
    }

    // ─────────────────────────────────────────────
    // activateOrder
    // ─────────────────────────────────────────────

    /** @test */
    public function test_staff_can_activate_an_order(): void
    {
        $support = $this->support();
        $ticket  = $this->makeTicket(['order_type' => 'recurrent']);

        $this->actingAs($support)
            ->patch(route('tickets.activate-order', $ticket->hashid))
            ->assertRedirect();

        $ticket->refresh();
        $this->assertNotEmpty($ticket->order_activations);
    }

    /** @test */
    public function test_regular_user_cannot_activate_order(): void
    {
        $user   = $this->regularUser();
        $ticket = $this->makeTicket(['order_type' => 'recurrent']);

        $this->actingAs($user)
            ->patch(route('tickets.activate-order', $ticket->hashid))
            ->assertSessionHas('error');
    }

    /** @test */
    public function test_activate_order_assigns_support_and_sets_status_to_in_progress(): void
    {
        $support = $this->support();
        $ticket  = Ticket::factory()->create([
            'order_type'        => 'recurrent',
            'recurrence_period' => 'monthly',
            'status'            => 'open',
            'attended_to_by'    => null,
        ]);

        $this->actingAs($support)
            ->patch(route('tickets.activate-order', $ticket->id));

        $ticket->refresh();
        $this->assertEquals('in-progress', $ticket->status);
        $this->assertIsArray($ticket->attended_to_by);
        $this->assertContains($support->id, $ticket->attended_to_by);
        $this->assertCount(1, $ticket->order_activations);
    }

    // ─────────────────────────────────────────────
    // searchTicketsByReference
    // ─────────────────────────────────────────────

    /** @test */
    public function test_can_search_ticket_by_valid_hashid(): void
    {
        $ticket = $this->makeTicket();

        $this->post(route('search-tickets'), ['reference' => $ticket->hashid])
            ->assertOk()
            ->assertViewIs('check-status')
            ->assertViewHas('tickets');
    }

    /** @test */
    public function test_search_with_invalid_reference_returns_empty_results(): void
    {
        $response = $this->post(route('search-tickets'), ['reference' => 'INVALID12'])->assertOk();
        $this->assertTrue($response->viewData('tickets')->isEmpty());
    }

    /** @test */
    public function test_search_reference_is_required_and_min_8_chars(): void
    {
        $this->post(route('search-tickets'), ['reference' => 'abc'])
            ->assertSessionHasErrors(['reference']);
    }
}
