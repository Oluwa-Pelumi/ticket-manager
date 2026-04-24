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
        // Create an admin user first
        $admin = User::factory()->create([
            'role'  => 'admin',
            'name'  => 'Admin User',
            'email' => 'admin@example.com',
        ]);

        // Create 10 regular users
        $users = User::factory(10)->create([
            'role' => 'user'
        ]);

        $priorities = ['low', 'medium', 'high', 'urgent'];
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
        $statuses   = ['open', 'in_progress', 'resolved', 'closed'];

        foreach ($users as $user) {
            // Create a ticket for each user
            $ticket = Ticket::create([
                'user_id' => $user->id,
                'subject' => $subjects[array_rand($subjects)],
                'content' => 'This is a sample ticket content for testing purposes. We are experiencing issues with the system.',
                'status' => $statuses[array_rand($statuses)],
                'priority' => $priorities[array_rand($priorities)],
                'attended_to_by' => $admin->id,
            ]);

            // Create a comment on the ticket from the user
            Comment::create([
                'ticket_id' => $ticket->id,
                'user_id' => $user->id,
                'content' => 'Please look into this issue as soon as possible.',
            ]);

            // Create a comment on the ticket from the admin
            Comment::create([
                'ticket_id' => $ticket->id,
                'user_id' => $admin->id,
                'content' => 'We have received your ticket and are looking into it.',
            ]);
        }
    }
}
