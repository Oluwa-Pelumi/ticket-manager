<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Faq;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Comment;
use App\Models\Category;
use App\Models\Programme;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Unit tests for all Eloquent models: relationships, casts, accessors, helpers.
 */
class ModelTest extends TestCase
{
    use RefreshDatabase;

    // ─────────────────────────────────────────────
    // User model
    // ─────────────────────────────────────────────

    /** @test */
    public function user_isAdmin_returns_true_for_admin_role(): void
    {
        $user = User::factory()->make(['role' => 'admin']);
        $this->assertTrue($user->isAdmin());
    }

    /** @test */
    public function user_isAdmin_returns_false_for_non_admin(): void
    {
        $user = User::factory()->make(['role' => 'user']);
        $this->assertFalse($user->isAdmin());
    }

    /** @test */
    public function user_isSupport_returns_true_for_support_role(): void
    {
        $user = User::factory()->make(['role' => 'support']);
        $this->assertTrue($user->isSupport());
    }

    /** @test */
    public function user_isSupport_returns_false_for_non_support(): void
    {
        $user = User::factory()->make(['role' => 'admin']);
        $this->assertFalse($user->isSupport());
    }

    /** @test */
    public function user_name_accessor_combines_first_middle_last(): void
    {
        $user = User::factory()->make([
            'first_name'  => 'Ada',
            'middle_name' => 'Grace',
            'last_name'   => 'Smith',
        ]);
        $this->assertEquals('Ada Grace Smith', $user->name);
    }

    /** @test */
    public function user_name_accessor_skips_null_middle_name(): void
    {
        $user = User::factory()->make([
            'first_name'  => 'Ada',
            'middle_name' => null,
            'last_name'   => 'Smith',
        ]);
        $this->assertEquals('Ada Smith', $user->name);
    }

    /** @test */
    public function user_has_many_tickets(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        Ticket::factory()->count(3)->create(['user_id' => $user->id]);

        $this->assertCount(3, $user->tickets);
        $this->assertInstanceOf(Ticket::class, $user->tickets->first());
    }

    /** @test */
    public function user_has_many_assigned_tickets(): void
    {
        $support = User::factory()->create(['role' => 'support']);
        Ticket::factory()->count(2)->create(['attended_to_by' => $support->id]);

        $this->assertCount(2, $support->assignedTickets);
    }

    /** @test */
    public function user_belongs_to_programme(): void
    {
        $programme = Programme::factory()->create();
        $user      = User::factory()->create(['role' => 'user', 'programme_id' => $programme->id]);

        $this->assertInstanceOf(Programme::class, $user->programme);
        $this->assertEquals($programme->id, $user->programme->id);
    }

    // ─────────────────────────────────────────────
    // Ticket model
    // ─────────────────────────────────────────────

    /** @test */
    public function ticket_hashid_attribute_is_not_empty(): void
    {
        $ticket = Ticket::factory()->create();
        $this->assertNotEmpty($ticket->hashid);
        $this->assertIsString($ticket->hashid);
    }

    /** @test */
    public function ticket_hashid_decodes_back_to_the_ticket_id(): void
    {
        $ticket  = Ticket::factory()->create();
        $decoded = \Vinkla\Hashids\Facades\Hashids::decode($ticket->hashid);
        $this->assertEquals($ticket->id, $decoded[0]);
    }

    /** @test */
    public function ticket_attachments_are_cast_to_array(): void
    {
        $ticket = Ticket::factory()->create(['attachments' => ['a.jpg', 'b.jpg']]);
        $this->assertIsArray($ticket->fresh()->attachments);
        $this->assertContains('a.jpg', $ticket->fresh()->attachments);
    }

    /** @test */
    public function ticket_belongs_to_user(): void
    {
        $user   = User::factory()->create(['role' => 'user']);
        $ticket = Ticket::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $ticket->user);
        $this->assertEquals($user->id, $ticket->user->id);
    }

    /** @test */
    public function ticket_belongs_to_attendant(): void
    {
        $support = User::factory()->create(['role' => 'support']);
        $ticket  = Ticket::factory()->create(['attended_to_by' => $support->id]);

        $this->assertInstanceOf(User::class, $ticket->attendant);
        $this->assertEquals($support->id, $ticket->attendant->id);
    }

    /** @test */
    public function ticket_can_have_multiple_attendants(): void
    {
        $support1 = User::factory()->create(['role' => 'support']);
        $support2 = User::factory()->create(['role' => 'support']);

        $ticket = Ticket::factory()->create(['attended_to_by' => $support1->id]);
        $ticket->addAttendant($support2->id);

        $this->assertCount(2, $ticket->attendants);
        $this->assertEquals($support1->id, $ticket->attendants[0]->id);
        $this->assertEquals($support2->id, $ticket->attendants[1]->id);
        $this->assertEquals($support2->id, $ticket->attendant->id);
    }

    /** @test */
    public function ticket_belongs_to_category(): void
    {
        $category = Category::factory()->create();
        $ticket   = Ticket::factory()->create(['category_id' => $category->id]);

        $this->assertInstanceOf(Category::class, $ticket->category);
        $this->assertEquals($category->id, $ticket->category->id);
    }

    /** @test */
    public function ticket_has_many_comments(): void
    {
        $ticket = Ticket::factory()->create();
        Comment::factory()->count(4)->create(['ticket_id' => $ticket->id]);

        $this->assertCount(4, $ticket->comments);
        $this->assertInstanceOf(Comment::class, $ticket->comments->first());
    }

    /** @test */
    public function ticket_resolves_route_binding_by_hashid(): void
    {
        $ticket   = Ticket::factory()->create();
        $resolved = (new Ticket())->resolveRouteBinding($ticket->hashid);
        $this->assertEquals($ticket->id, $resolved->id);
    }

    /** @test */
    public function ticket_has_support_replied_is_false_with_no_comments(): void
    {
        $ticket = Ticket::factory()->create();
        $this->assertFalse($ticket->has_support_replied);
    }

    /** @test */
    public function ticket_has_support_replied_is_true_when_support_commented(): void
    {
        $support = User::factory()->create(['role' => 'support']);
        $ticket  = Ticket::factory()->create();
        Comment::factory()->create(['ticket_id' => $ticket->id, 'user_id' => $support->id]);

        $this->assertTrue($ticket->fresh()->has_support_replied);
    }

    /** @test */
    public function ticket_has_support_replied_is_false_when_only_user_commented(): void
    {
        $user    = User::factory()->create(['role' => 'user']);
        $ticket  = Ticket::factory()->create(['user_id' => $user->id]);
        Comment::factory()->create(['ticket_id' => $ticket->id, 'user_id' => $user->id]);

        $this->assertFalse($ticket->fresh()->has_support_replied);
    }

    // ─────────────────────────────────────────────
    // Comment model
    // ─────────────────────────────────────────────

    /** @test */
    public function comment_belongs_to_ticket(): void
    {
        $ticket  = Ticket::factory()->create();
        $comment = Comment::factory()->create(['ticket_id' => $ticket->id]);

        $this->assertInstanceOf(Ticket::class, $comment->ticket);
        $this->assertEquals($ticket->id, $comment->ticket->id);
    }

    /** @test */
    public function comment_belongs_to_user(): void
    {
        $user    = User::factory()->create(['role' => 'user']);
        $comment = Comment::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $comment->user);
        $this->assertEquals($user->id, $comment->user->id);
    }

    /** @test */
    public function comment_attachments_are_cast_to_array(): void
    {
        $comment = Comment::factory()->create(['attachments' => ['img1.jpg']]);
        $this->assertIsArray($comment->fresh()->attachments);
        $this->assertContains('img1.jpg', $comment->fresh()->attachments);
    }

    /** @test */
    public function comment_user_can_be_null_for_guests(): void
    {
        $comment = Comment::factory()->create(['user_id' => null]);
        $this->assertNull($comment->user);
    }

    // ─────────────────────────────────────────────
    // Category model
    // ─────────────────────────────────────────────

    /** @test */
    public function category_has_many_tickets(): void
    {
        $category = Category::factory()->create();
        Ticket::factory()->count(3)->create(['category_id' => $category->id]);

        $this->assertCount(3, $category->tickets);
        $this->assertInstanceOf(Ticket::class, $category->tickets->first());
    }

    /** @test */
    public function category_fillable_includes_name_and_slug(): void
    {
        $category = Category::factory()->create(['name' => 'Test', 'slug' => 'test']);
        $this->assertEquals('Test', $category->name);
        $this->assertEquals('test', $category->slug);
    }

    // ─────────────────────────────────────────────
    // Faq model
    // ─────────────────────────────────────────────

    /** @test */
    public function faq_can_be_created_with_fillable_attributes(): void
    {
        Faq::factory()->create([
            'question' => 'How do I submit a ticket?',
            'answer'   => 'Use the Submit Ticket page.',
            'order'    => 1,
        ]);

        $this->assertDatabaseHas('faqs', [
            'question' => 'How do I submit a ticket?',
            'order'    => 1,
        ]);
    }

    // ─────────────────────────────────────────────
    // Programme model
    // ─────────────────────────────────────────────

    /** @test */
    public function programme_can_be_created_with_name_and_slug(): void
    {
        $programme = Programme::factory()->create(['name' => 'Dentistry', 'slug' => 'computer-science']);
        $this->assertDatabaseHas('programmes', ['name' => 'Dentistry', 'slug' => 'computer-science']);
    }

    /** @test */
    public function programme_has_many_users(): void
    {
        $programme = Programme::factory()->create();
        User::factory()->count(3)->create(['role' => 'user', 'programme_id' => $programme->id]);

        $this->assertCount(3, $programme->users);
        $this->assertInstanceOf(User::class, $programme->users->first());
    }
}
