<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        $support1 = User::where('email', 'support1@laradocs.test')->first();
        $support2 = User::where('email', 'support2@laradocs.test')->first();
        $user1    = User::where('email', 'user1@laradocs.test')->first();
        $user2    = User::where('email', 'user2@laradocs.test')->first();

        // Helper: create a poster-then-reply thread on a ticket
        $thread = function (Ticket $ticket, ?User $poster, string $posterMsg, ?User $staff, string $staffMsg) {
            // Initial message from the ticket poster
            Comment::create([
                'ticket_id' => $ticket->id,
                'user_id'   => $poster?->id,
                'content'   => $posterMsg,
                'attachments'    => null,
            ]);

            // Follow-up reply from the assigned support staff (if any)
            if ($staff) {
                Comment::create([
                    'ticket_id'  => $ticket->id,
                    'user_id'    => $staff->id,
                    'content'    => $staffMsg,
                    'attachments'     => null,
                ]);
            }
        };

        // ─── Ticket 1 – Transcript Request (Emeka / support1, in-progress) ──────────────────
        $t = Ticket::where('subject', 'transcript-request')->where('user_id', $user1?->id)->where('status', 'in-progress')->first();
        if ($t) {
            $thread(
                $t, $user1,
                'Hi, just following up on my undergraduate transcript request. My application portal closes in three days and I want to verify if the digital copy has been sent.',
                $support1,
                'Hello Emeka, we have received your request and the registry has processed it. The official transcript has been uploaded and sent directly to the University of Lagos admissions email. You should have also received a confirmation email from us.'
            );
            // Poster's second follow-up
            Comment::create([
                'ticket_id' => $t->id,
                'user_id'   => $user1?->id,
                'content'   => 'Thank you so much! That is a relief. I will check the portal to confirm.',
            ]);
        }

        // ─── Ticket 2 – Transcript Request (Emeka / support2, closed) ─────────────────
        $t = Ticket::where('subject', 'transcript-request')->where('user_id', $user1?->id)->where('status', 'closed')->first();
        if ($t) {
            $thread(
                $t, $user1,
                'Quick clarification: does the transcript copy contain course descriptions for elective courses as well, or only core courses?',
                $support2,
                'Good question, Emeka. The transcript includes detailed descriptions, credit units, and outlines for all courses — both core and electives — offered during the 2024 session. Let us know if your transfer institution requires any additional accreditation details.'
            );
        }

        // ─── Ticket 3 – Letter of Recommendation Request (Emeka / support2, open) ───
        $t = Ticket::where('subject', 'letter-of-recommendation-request')->where('user_id', $user1?->id)->where('status', 'open')->first();
        if ($t) {
            Comment::create([
                'ticket_id' => $t->id,
                'user_id'   => $user1?->id,
                'content'   => 'I should mention that the scholarship board requires the recommendation to be on Dr. Adewale\'s official letterhead. Please ensure he is aware of this.',
            ]);
        }

        // ─── Ticket 4 – Certificate Request (Emeka / support1, in-progress) ──────────
        $t = Ticket::where('subject', 'certificate-request')->where('user_id', $user1?->id)->where('status', 'in-progress')->first();
        if ($t) {
            $thread(
                $t, $user1,
                'I would prefer deliveries on Mondays before noon if possible. Can you advise on the estimated transit time for delivery within Abuja?',
                $support1,
                'Hi Emeka, we have set up the dispatch via our express courier partner. Deliveries within Abuja typically take 24–48 hours once printed. We will send a tracking link to your email as soon as it leaves the registry.'
            );
        }

        // ─── Ticket 5 – Statement of Result Request (Emeka / support2, closed) ───────────────────
        $t = Ticket::where('subject', 'statement-of-result-request')->where('user_id', $user1?->id)->where('status', 'closed')->first();
        if ($t) {
            $thread(
                $t, $user1,
                'My clearance was completed last Tuesday, but my dashboard still says "pending statement printing". Can you verify if there is any outstanding document?',
                $support2,
                'Emeka, I checked with the exams and records unit. There was a temporary delay in generating the digital copy due to a system update, but it has now been resolved. I have sent the Statement of Result directly to your registered email address and updated your status.'
            );
            Comment::create([
                'ticket_id' => $t->id,
                'user_id'   => $user1?->id,
                'content'   => 'It arrived! Thank you for the prompt action. I appreciate it.',
            ]);
        }

        // ─── Ticket 6 – Transcript Request (Fatima / support2, open) ──────────────────────────
        $t = Ticket::where('subject', 'transcript-request')->where('user_id', $user2?->id)->where('status', 'open')->first();
        if ($t) {
            Comment::create([
                'ticket_id' => $t->id,
                'user_id'   => $user2?->id,
                'content'   => 'The WES evaluation deadline is fast approaching. Can I pay for express processing online, or do I need to send someone to the bank on campus?',
            ]);
        }

        // ─── Ticket 7 – Certificate Request (Fatima / support1, in-progress) ────────────────
        $t = Ticket::where('subject', 'certificate-request')->where('user_id', $user2?->id)->where('status', 'in-progress')->first();
        if ($t) {
            $thread(
                $t, $user2,
                'Could you also include the official academic robes hire receipt in our package? Most of us are preparing for the upcoming convocation ceremony.',
                $support1,
                'Hello Fatima, absolutely. We can package the convocation hire receipts alongside your certificates. I have updated the bulk collection list and will make sure all 5 alumni packages are bundled together. First courier batch is scheduled for this Friday.'
            );
            Comment::create([
                'ticket_id' => $t->id,
                'user_id'   => $user2?->id,
                'content'   => 'Perfect! Thank you so much for arranging that. We really appreciate it.',
            ]);
        }

        // ─── Ticket 8 – Transcript Request (Fatima / support2, closed) ─────────────────
        $t = Ticket::where('subject', 'transcript-request')->where('user_id', $user2?->id)->where('status', 'closed')->first();
        if ($t) {
            $thread(
                $t, $user2,
                'I need this for the 2021 curriculum. Does it contain the signatures of the department board members?',
                $support2,
                'Hi Fatima, yes. The transcript package we provide is the officially approved senate version, which includes the signed page by the Dean and HOD. It is fully certified for board certification purposes.'
            );
        }

        // ─── Ticket 9 – Letter of Recommendation Request (Fatima / support1, in-progress) ────
        $t = Ticket::where('subject', 'letter-of-recommendation-request')->where('user_id', $user2?->id)->where('status', 'in-progress')->first();
        if ($t) {
            $thread(
                $t, $user2,
                'I need to confirm if the HOD has uploaded the reference directly to the portal or if it was sent via email.',
                $support1,
                'Hello Fatima, the HOD has uploaded the signed reference letter directly to your scholarship portal. The system has marked it as completed. Best of luck with your application!'
            );
            Comment::create([
                'ticket_id' => $t->id,
                'user_id'   => $user2?->id,
                'content'   => 'Thank you for the update! I can see it on my portal now.',
            ]);
        }

        // ─── Ticket 10 – Statement of Result Request (Fatima / support2, open) ─────────────────────
        $t = Ticket::where('subject', 'statement-of-result-request')->where('user_id', $user2?->id)->where('status', 'open')->first();
        if ($t) {
            Comment::create([
                'ticket_id' => $t->id,
                'user_id'   => $user2?->id,
                'content'   => 'Is there any additional charge for extra copies of the Statement of Result? I might need three copies.',
            ]);
        }
    }
}
