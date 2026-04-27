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
            'insurance_claim',
            'referral_request',
            'file_a_complaint',
            'general_feedback',
            'cancel_appointment',
            'request_lab_results',
            'post_surgery_concern',
            'book_new_appointment',
            'billing_payment_issue',
            'reschedule_appointment',
            'request_medical_report',
            'follow_up_consultation',
            'urgent_medical_inquiry',
            'request_medical_records',
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
                'whatsapp_number' => '+23480' . $faker->randomNumber(8, true),
                'subject'         => $subjects[array_rand($subjects)],
                'content'         => $faker->paragraph(3) . "\n\nWe are experiencing issues.",
                'status'          => $statuses[array_rand($statuses)],
                'priority'        => $priorities[array_rand($priorities)],
                'attended_to_by'  => $assignedAdmin->id,
            ]);

            // Create a comment on the ticket from the guest
            Comment::create([
                'ticket_id' => $ticket->id,
                'user_id'   => null,
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
