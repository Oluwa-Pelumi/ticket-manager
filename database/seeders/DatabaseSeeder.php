<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Ticket;
use App\Models\Comment;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    // use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        // Create multiple admins to test load balancing
        $admins = collect();
        $admins->push(User::factory()->create([
            'role'  => 'admin',
            'name'  => 'Admin User',
            'email' => 'admin@example.com',
        ]));
        $admins->push(User::factory()->create([
            'role'  => 'admin',
            'name'  => 'Support Specialist',
            'email' => 'support@example.com',
        ]));
        $admins->push(User::factory()->create([
            'role'  => 'admin',
            'name'  => 'Tech Lead',
            'email' => 'tech@example.com',
        ]));

        $priorities = ['low', 'medium', 'high'];
        $subjects   = [
            'missed_dose',
            'running_out',
            'wrong_dosage',
            'side_effects',
            'refill_request',
            'wrong_medication',
            'drug_interaction',
            'allergic_reaction',
            'drug_interactions',
            'prescription_expired',
            'unclear_instructions',
            'speak_with_pharmacist',
            'transfer_prescription',
            'storage_handling_questions',
            'difficulty_using_drug_form',

        ];
        $statuses   = ['open', 'in-progress', 'closed'];

        for ($i = 0; $i < 20; $i++) {
            // Find the admin with the least ticket load
            $adminsList = User::where('role', 'admin')
                ->withCount(['assignedTickets' => function ($query) {
                    $query->whereIn('status', ['open', 'in-progress']);
                }])
                ->get();
            
            $minCount = $adminsList->min('assigned_tickets_count');
            $assignedAdmin = $adminsList->where('assigned_tickets_count', $minCount)->random();

            // Create a ticket for a guest user
            $ticket = Ticket::create([
                'user_id'         => null,
                'name'            => $faker->name,
                'email'           => $faker->safeEmail,
                'attended_to_by'  => $assignedAdmin->id,
                'subject'         => $subjects[array_rand($subjects)],
                'status'          => $statuses[array_rand($statuses)],
                'priority'        => $priorities[array_rand($priorities)],
                'whatsapp_number' => '+23480' . $faker->randomNumber(8, true),
                'content'         => $faker->paragraph(3) . "\n\nWe are experiencing issues.",
            ]);

            // Create a comment on the ticket from the guest
            Comment::create([
                'user_id'   => null,
                'ticket_id' => $ticket->id,
                'content'   => 'Please look into this issue as soon as possible. ' . $faker->sentence,
            ]);

            // Create a comment on the ticket from the assigned admin
            Comment::create([
                'ticket_id' => $ticket->id,
                'user_id'   => $assignedAdmin->id,
                'content'   => 'We have received your ticket and are looking into it.',
            ]);
        }
    }
}
