<?php

namespace Database\Factories;

use App\Models\Ticket;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ticket>
 */
class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    public function definition(): array
    {
        return [
            'name'                   => fake()->name(),
            'email'                  => fake()->safeEmail(),
            'subject'                => fake()->randomElement(['Transcript Request', 'Certificate Request', 'Letter of Recommendation Request', 'Syllabus Request', 'Statement of Result Request', 'Other']),
            'content'                => fake()->paragraph(),
            'status'                 => fake()->randomElement(['open', 'in-progress', 'closed']),
            'priority'               => fake()->randomElement(['low', 'medium', 'high']),
            'user_id'                => null,
            'attended_to_by'         => null,
            'category_id'            => null,
            'images'                 => [],
            'phone_number'           => null,
            'filename'               => null,
        ];
    }

    /** State: open ticket. */
    public function open(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'open',
        ]);
    }

    /** State: closed ticket. */
    public function closed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'closed',
        ]);
    }

    /** State: in-progress ticket. */
    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'in-progress',
        ]);
    }

    /** State: ticket with a registered user. */
    public function forUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
            'name'    => $user->name,
            'email'   => $user->email,
        ]);
    }

    /** State: ticket assigned to a support member. */
    public function assignedTo(User $support): static
    {
        return $this->state(fn (array $attributes) => [
            'attended_to_by' => $support->id,
        ]);
    }
}
