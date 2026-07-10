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

        // ─── Ticket 1 – Transcript Request (Emeka / support1) ──────────────────
        $t = Ticket::where('subject', 'Urgent Undergraduate Transcript Request')->first();
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

        // ─── Ticket 2 – Syllabus Copy (Emeka / support2) ─────────────────
        $t = Ticket::where('subject', 'Syllabus Copy for Year 3 Courses')->first();
        if ($t) {
            $thread(
                $t, $user1,
                'Quick clarification: does the syllabus copy contain course descriptions for elective courses as well, or only core courses?',
                $support2,
                'Good question, Emeka. The syllabus booklet includes detailed descriptions, credit units, and outlines for all courses — both core and electives — offered during the 2024 session. Let us know if your transfer institution requires any additional accreditation details.'
            );
        }

        // ─── Ticket 3 – Dr. Adewale Recommendation (Emeka / unassigned, open) ───
        $t = Ticket::where('subject', 'Recommendation Letter Request - Dr. Adewale')->first();
        if ($t) {
            Comment::create([
                'ticket_id' => $t->id,
                'user_id'   => $user1?->id,
                'content'   => 'I should mention that the scholarship board requires the recommendation to be on Dr. Adewale\'s official letterhead. Please ensure he is aware of this.',
            ]);
        }

        // ─── Ticket 4 – Certificate Collection (Emeka / support1) ──────────
        $t = Ticket::where('subject', 'B.Sc. Degree Certificate Collection')->first();
        if ($t) {
            $thread(
                $t, $user1,
                'I would prefer deliveries on Mondays before noon if possible. Can you advise on the estimated transit time for delivery within Abuja?',
                $support1,
                'Hi Emeka, we have set up the dispatch via our express courier partner. Deliveries within Abuja typically take 24–48 hours once printed. We will send a tracking link to your email as soon as it leaves the registry.'
            );
        }

        // ─── Ticket 5 – Statement of Result (Emeka / support2) ───────────────────
        $t = Ticket::where('subject', 'Statement of Result Status Update')->first();
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

        // ─── Ticket 6 – WES Transcript (Fatima / open) ──────────────────────────
        $t = Ticket::where('subject', 'Official Transcript for WES Evaluation')->first();
        if ($t) {
            Comment::create([
                'ticket_id' => $t->id,
                'user_id'   => $user2?->id,
                'content'   => 'The WES evaluation deadline is fast approaching. Can I pay for express processing online, or do I need to send someone to the bank on campus?',
            ]);
        }

        // ─── Ticket 7 – Bulk Certificates (Fatima / support1) ────────────────
        $t = Ticket::where('subject', 'Bulk Certificate Requests for Alumni Group')->first();
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

        // ─── Ticket 8 – Chemistry Syllabus (Fatima / support2) ─────────────────
        $t = Ticket::where('subject', 'Detailed Syllabus for Chemistry Department')->first();
        if ($t) {
            $thread(
                $t, $user2,
                'I need this for the 2021 curriculum. Does it contain the signatures of the department board members?',
                $support2,
                'Hi Fatima, yes. The syllabus package we provide is the officially approved senate version, which includes the signed page by the Dean and HOD. It is fully certified for board certification purposes.'
            );
        }

        // ─── Ticket 9 – HOD Reference (Fatima / support1) ────
        $t = Ticket::where('subject', 'Academic Reference Letter Status')->first();
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

        // ─── Ticket 10 – Statement of Result Request (Fatima / open) ─────────────────────
        $t = Ticket::where('subject', 'Statement of Result Copy Request')->first();
        if ($t) {
            Comment::create([
                'ticket_id' => $t->id,
                'user_id'   => $user2?->id,
                'content'   => 'Is there any additional charge for extra copies of the Statement of Result? I might need three copies.',
            ]);
        }

        // ─── Ticket 12 – Replacement Certificate (guest / support2) ─────────────────
        $t = Ticket::where('subject', 'Replacement of Damaged Degree Certificate')->first();
        if ($t) {
            Comment::create([
                'ticket_id' => $t->id,
                'user_id'   => $support2?->id,
                'content'   => 'Hello, we have received your request for a replacement certificate. Please upload a copy of the police report, the court affidavit, and proof of payment. Once verified, the printing will take 5 working days.',
            ]);
        }

        // ─── Ticket 13 – Reference Study Abroad (guest / support1) ────────────────
        $t = Ticket::where('subject', 'Reference for Postgrad Studies Abroad')->first();
        if ($t) {
            Comment::create([
                'ticket_id' => $t->id,
                'user_id'   => $support1?->id,
                'content'   => 'Hello Bello, the system automatically dispatches reference requests to the designated lecturers\' university emails once you submit their details. Please ensure they check their spam folders if they have not received the links.',
            ]);
        }

        // ─── Ticket 14 – Engineering Syllabus (guest / support2) ────────────
        $t = Ticket::where('subject', 'Engineering Syllabus Accreditation Query')->first();
        if ($t) {
            Comment::create([
                'ticket_id' => $t->id,
                'user_id'   => $support2?->id,
                'content'   => 'Hi Adaora, yes, the 2019 Civil Engineering syllabus contains the official NBA accreditation certificate and the COREN approval letter. This is sufficient for visa and credential evaluation purposes.',
            ]);
        }

        // ─── Ticket 17 – Name Correction (guest / support2) ─────────────────
        $t = Ticket::where('subject', 'Correction of Name on Portal')->first();
        if ($t) {
            Comment::create([
                'ticket_id' => $t->id,
                'user_id'   => $support2?->id,
                'content'   => 'Hello Tunde, your name has been corrected on the portal to "Babatunde Fashola" as requested. This will reflect on any subsequent documents printed.',
            ]);
        }

        // ─── Ticket 19 – VC Recommendation (guest / support1) ───────────────
        $t = Ticket::where('subject', 'Recommendation from Vice Chancellor')->first();
        if ($t) {
            Comment::create([
                'ticket_id' => $t->id,
                'user_id'   => $support1?->id,
                'content'   => 'Hello Obiageli, requests for the VC\'s recommendation should be directed to the VC\'s Principal Assistant. Please forward your CV, research proposal, and department recommendation letter to vc-office@university.edu.',
            ]);
        }

        // ─── Ticket 20 – Course Description Booklet (guest / support2) ──────────
        $t = Ticket::where('subject', 'Course Description Booklet Request')->first();
        if ($t) {
            Comment::create([
                'ticket_id' => $t->id,
                'user_id'   => $support2?->id,
                'content'   => 'Hello Emmanuel, we have uploaded the digital PDF version of the 2015-2019 Business Administration course description booklet. You can download it directly from the link provided.',
            ]);
        }
    }
}
