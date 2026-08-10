<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Ticket;
use App\Models\Category;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        $user1   = User::where('email', 'user1@laradocs.test')->first();
        $user2   = User::where('email', 'user2@laradocs.test')->first();

        $support1 = User::where('email', 'support1@laradocs.test')->first();
        $support2 = User::where('email', 'support2@laradocs.test')->first();

        $transcript     = Category::where('slug', 'transcript-request')->first();
        $certificate    = Category::where('slug', 'certificate-request')->first();
        $recommendation = Category::where('slug', 'letter-of-recommendation-request')->first();
        $statement      = Category::where('slug', 'statement-of-result-request')->first();

        $tickets = [
            // ── Emeka's tickets (user1) ──────────────────────────────────────
            [
                'user_id'        => $user1?->id,
                'category_id'    => $transcript?->id,
                'subject'        => 'transcript-request',
                'content'        => 'I need my official undergraduate transcript sent to the University of Lagos for my postgraduate application. My admission deadline is in two weeks. Please process this as soon as possible.',
                'priority'       => 'high',
                'status'         => 'in-progress',
                'attended_to_by' => [$support1?->id, $support2?->id],
            ],
            [
                'user_id'        => $user1?->id,
                'category_id'    => $transcript?->id,
                'subject'        => 'transcript-request',
                'content'        => 'I am applying for a credit transfer and need the official transcript copy for all my third-year Dentistry courses from the 2024 academic session.',
                'priority'       => 'medium',
                'status'         => 'closed',
                'attended_to_by' => [$support2?->id],
            ],
            [
                'user_id'        => $user1?->id,
                'category_id'    => $recommendation?->id,
                'subject'        => 'letter-of-recommendation-request',
                'content'        => 'I submitted a request for a letter of recommendation from Dr. Adewale for my Commonwealth Scholarship application. Could you please confirm if the letter has been signed and uploaded?',
                'priority'       => 'high',
                'status'         => 'open',
                'attended_to_by' => $support2?->id,
            ],
            [
                'user_id'        => $user1?->id,
                'category_id'    => $certificate?->id,
                'subject'        => 'certificate-request',
                'content'        => 'I completed my program last session and would like to request the printing and collection of my original B.Sc. certificate. Can this be dispatched via courier?',
                'priority'       => 'medium',
                'status'         => 'in-progress',
                'attended_to_by' => $support1?->id,
            ],
            [
                'user_id'        => $user1?->id,
                'category_id'    => $statement?->id,
                'subject'        => 'statement-of-result-request',
                'content'        => 'My statement of result was approved last Tuesday, but I have not received the digital copy via email yet. Could you please check on the status and send it?',
                'priority'       => 'low',
                'status'         => 'closed',
                'attended_to_by' => $support2?->id,
            ],

            // ── Fatima's tickets (user2) ─────────────────────────────────────
            [
                'user_id'        => $user2?->id,
                'category_id'    => $transcript?->id,
                'subject'        => 'transcript-request',
                'content'        => 'I need my academic transcript sent directly to World Education Services (WES) for evaluation. I have my WES reference number ready. Can I submit it without coming to the campus?',
                'priority'       => 'high',
                'status'         => 'open',
                'attended_to_by' => $support2?->id,
            ],
            [
                'user_id'        => $user2?->id,
                'category_id'    => $certificate?->id,
                'subject'        => 'certificate-request',
                'content'        => 'I am coordinating the certificate requests for a group of 5 alumni. Can we submit these in bulk and get a unified tracking reference for the courier dispatch?',
                'priority'       => 'low',
                'status'         => 'in-progress',
                'attended_to_by' => $support1?->id,
            ],
            [
                'user_id'        => $user2?->id,
                'category_id'    => $transcript?->id,
                'subject'        => 'transcript-request',
                'content'        => 'For my professional board certification, I require the detailed transcript for the entire Chemistry program of the 2021 graduating set. What is the standard processing time?',
                'priority'       => 'medium',
                'status'         => 'closed',
                'attended_to_by' => $support2?->id,
            ],
            [
                'user_id'        => $user2?->id,
                'category_id'    => $recommendation?->id,
                'subject'        => 'letter-of-recommendation-request',
                'content'        => 'I requested an academic reference letter from the Head of Department. I need to make sure it includes the official letterhead and stamp. How can I verify this?',
                'priority'       => 'high',
                'status'         => 'in-progress',
                'attended_to_by' => $support1?->id,
            ],
            [
                'user_id'        => $user2?->id,
                'category_id'    => $statement?->id,
                'subject'        => 'statement-of-result-request',
                'content'        => 'I need a certified true copy of my Statement of Result for a job application. Please let me know how to make the payment and obtain it.',
                'priority'       => 'medium',
                'status'         => 'open',
                'attended_to_by' => $support2?->id,
            ],
        ];

        foreach ($tickets as $data) {
            Ticket::create($data);
        }
    }
}
