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
        $support1 = User::where('email', 'support1@laradrug.test')->first();
        $support2 = User::where('email', 'support2@laradrug.test')->first();
        $user1    = User::where('email', 'user1@laradrug.test')->first();
        $user2    = User::where('email', 'user2@laradrug.test')->first();

        // Helper: create a poster-then-reply thread on a ticket
        $thread = function (Ticket $ticket, ?User $poster, string $posterMsg, ?User $staff, string $staffMsg) {
            // Initial message from the ticket poster
            Comment::create([
                'ticket_id' => $ticket->id,
                'user_id'   => $poster?->id,
                'content'   => $posterMsg,
                'attachments' => null,
            ]);

            // Follow-up reply from the assigned support staff (if any)
            if ($staff) {
                Comment::create([
                    'ticket_id'  => $ticket->id,
                    'user_id'    => $staff->id,
                    'content'    => $staffMsg,
                    'attachments' => null,
                ]);
            }
        };

        // ─── Ticket 1 – Metformin Refill (Emeka / support1) ──────────────────
        $t = Ticket::where('subject', 'Monthly Metformin Refill Request')->first();
        if ($t) {
            $thread(
                $t, $user1,
                'Hi, just following up on my Metformin refill request. My current supply will last only two more days. Please let me know if anything is needed from my side.',
                $support1,
                'Hello Emeka, we have received your request and are processing it now. Your Metformin 500mg (90-tablet pack) will be ready for delivery by tomorrow morning. You will receive a WhatsApp notification once it has been dispatched.'
            );
            // Poster's second follow-up
            Comment::create([
                'ticket_id' => $t->id,
                'user_id'   => $user1?->id,
                'content'   => 'Thank you so much! That is a relief. I will be home all day tomorrow.',
            ]);
        }

        // ─── Ticket 2 – Lisinopril Dosage (Emeka / support2) ─────────────────
        $t = Ticket::where('subject', 'Lisinopril Dosage Enquiry')->first();
        if ($t) {
            $thread(
                $t, $user1,
                'Quick clarification: my prescription note says "once daily" but does not specify AM or PM. Does it matter for Lisinopril 10mg?',
                $support2,
                'Good question, Emeka. Lisinopril is generally more effective when taken in the evening for blood pressure control, as blood pressure naturally rises in the early morning hours. However, either timing works — consistency is the most important factor. If you experience any dizziness, take it at bedtime. Please consult your doctor if symptoms persist.'
            );
        }

        // ─── Ticket 3 – Medication Interactions (Emeka / unassigned, open) ───
        $t = Ticket::where('subject', 'Seeking Advice on Medication Interactions')->first();
        if ($t) {
            Comment::create([
                'ticket_id' => $t->id,
                'user_id'   => $user1?->id,
                'content'   => 'I should also mention that I occasionally take Aspirin 75mg for cardiovascular protection. Please factor that into your advice as well.',
            ]);
        }

        // ─── Ticket 4 – Amlodipine Weekly Order (Emeka / support1) ──────────
        $t = Ticket::where('subject', 'Amlodipine Weekly Order')->first();
        if ($t) {
            $thread(
                $t, $user1,
                'I would prefer deliveries on Mondays before noon if possible. Also, should I always expect the same quantity of 30 tablets per delivery?',
                $support1,
                'Hi Emeka, we have set your recurring order to deliver every Monday morning. Each delivery will contain 30 tablets of Amlodipine 5mg as agreed. We will send a WhatsApp reminder the evening before each dispatch. Let us know if you need to adjust the quantity at any time.'
            );
        }

        // ─── Ticket 5 – Delivery Status (Emeka / support2) ───────────────────
        $t = Ticket::where('subject', 'Delivery Status for Last Order')->first();
        if ($t) {
            $thread(
                $t, $user1,
                'My order reference is from last Tuesday. I stayed home all day but no one came. There was also no notification on WhatsApp.',
                $support2,
                'Emeka, I sincerely apologise for the inconvenience. Our delivery partner had a vehicle breakdown on Tuesday that caused widespread delays. Your order has been rescheduled and will be delivered today between 2 PM and 5 PM. I have also flagged your account for priority dispatch going forward.'
            );
            Comment::create([
                'ticket_id' => $t->id,
                'user_id'   => $user1?->id,
                'content'   => 'It arrived! Thank you for the prompt action. I appreciate it.',
            ]);
        }

        // ─── Ticket 6 – Amoxicillin (Fatima / open) ──────────────────────────
        $t = Ticket::where('subject', 'Amoxicillin for Dental Infection')->first();
        if ($t) {
            Comment::create([
                'ticket_id' => $t->id,
                'user_id'   => $user2?->id,
                'content'   => 'The pain has become quite severe. Is there any chance I can get the medication today? I can provide my dentist\'s contact for verification if needed.',
            ]);
        }

        // ─── Ticket 7 – Paracetamol Bulk (Fatima / support1) ────────────────
        $t = Ticket::where('subject', 'Paracetamol Bulk Order for Family')->first();
        if ($t) {
            $thread(
                $t, $user2,
                'Could you also include some ORS sachets in the monthly order? My children often get dehydrated when they are unwell.',
                $support1,
                'Hello Fatima, absolutely — we can add ORS (Oral Rehydration Salts) sachets to your monthly bundle. I will include 10 sachets alongside the 60 Paracetamol tablets. Your first delivery is scheduled for this Friday. Please confirm your delivery address is still correct.'
            );
            Comment::create([
                'ticket_id' => $t->id,
                'user_id'   => $user2?->id,
                'content'   => 'Yes, the address is correct. Thank you for accommodating the extra request!',
            ]);
        }

        // ─── Ticket 8 – Insulin Storage (Fatima / support2) ─────────────────
        $t = Ticket::where('subject', 'Storage Requirements for Insulin')->first();
        if ($t) {
            $thread(
                $t, $user2,
                'We have about 6 hours of power daily. I currently keep the insulin in a small cooler with ice packs. Is that sufficient?',
                $support2,
                'Hi Fatima, using a cooler with ice packs is a valid short-term strategy. Unopened insulin vials should be kept between 2–8°C (in a fridge). Once opened, a vial can be kept at room temperature (below 25°C) for up to 28 days. For longer power outages, consider a small battery-powered medical refrigerator or a clay pot cooler as a low-cost alternative. Please ensure ice packs do not directly touch the vials to prevent freezing, as frozen insulin must not be used.'
            );
        }

        // ─── Ticket 9 – Safe Analgesics in Pregnancy (Fatima / support1) ────
        $t = Ticket::where('subject', 'Safe Analgesics During Pregnancy')->first();
        if ($t) {
            $thread(
                $t, $user2,
                'I have also been using a hot water bottle which helps a bit, but the pain returns quickly. What can I safely combine with it?',
                $support1,
                'Hello Fatima, Paracetamol (acetaminophen) is considered the first-line pain reliever during pregnancy, including the first trimester, when taken at the recommended dose (500mg–1g, up to 4 times daily). Avoid NSAIDs (ibuprofen, naproxen, aspirin) throughout pregnancy, especially after 20 weeks. Gentle prenatal yoga and warm (not hot) compresses are also safe complements. Please do consult your obstetrician before starting any new medication.'
            );
            Comment::create([
                'ticket_id' => $t->id,
                'user_id'   => $user2?->id,
                'content'   => 'Thank you, this is very helpful! I will stick with Paracetamol and mention it to my OB at my next visit.',
            ]);
        }

        // ─── Ticket 10 – Iron Supplement (Fatima / open) ─────────────────────
        $t = Ticket::where('subject', 'Iron Supplement Subscription')->first();
        if ($t) {
            Comment::create([
                'ticket_id' => $t->id,
                'user_id'   => $user2?->id,
                'content'   => 'I should mention that I have had an upset stomach with iron tablets in the past. Are there any formulations that are gentler on the stomach?',
            ]);
        }

        // ─── Ticket 12 – Insulin Glargine (guest / support2) ─────────────────
        $t = Ticket::where('subject', 'Urgent Insulin Glargine Order')->first();
        if ($t) {
            Comment::create([
                'ticket_id' => $t->id,
                'user_id'   => $support2?->id,
                'content'   => 'Hello, we have located a pack of Insulin Glargine (Lantus) 100 units/ml in our cold store. We can dispatch it via express courier for delivery within 2–3 hours. Could you please confirm your delivery address and whether you need any needles included? We will call you immediately to confirm.',
            ]);
        }

        // ─── Ticket 13 – Herbal & Warfarin (guest / support1) ────────────────
        $t = Ticket::where('subject', 'Mixing Herbal Remedies with Warfarin')->first();
        if ($t) {
            Comment::create([
                'ticket_id' => $t->id,
                'user_id'   => $support1?->id,
                'content'   => 'This is an important concern, Bello. Many herbal teas — especially those containing ginger, green tea, chamomile, or ginkgo — can significantly affect INR when combined with Warfarin. We strongly advise against taking any herbal supplement without first checking with your anticoagulation clinic or prescribing physician. It is safest to avoid the tea until you get formal approval. Please do not adjust your Warfarin dose yourself if you notice any unusual bruising or bleeding.',
            ]);
        }

        // ─── Ticket 14 – Prescription Validity (guest / support2) ────────────
        $t = Ticket::where('subject', 'Prescription Validity Period')->first();
        if ($t) {
            Comment::create([
                'ticket_id' => $t->id,
                'user_id'   => $support2?->id,
                'content'   => 'Hi Adaora, in Nigeria the standard validity period for a prescription is typically 3 months from the date of issue, unless it is for a controlled substance (which may have a shorter window) or the prescriber has indicated otherwise. Your three-month-old prescription is right at the boundary — I would recommend visiting your doctor for a renewal to be safe, especially if it is for a chronic condition requiring regular monitoring.',
            ]);
        }

        // ─── Ticket 17 – Generic vs Brand (guest / support2) ─────────────────
        $t = Ticket::where('subject', 'Generic vs Brand Medication Query')->first();
        if ($t) {
            Comment::create([
                'ticket_id' => $t->id,
                'user_id'   => $support2?->id,
                'content'   => 'Hello Tunde, yes we stock generic Losartan Potassium 50mg and 100mg. Generic medications contain the same active ingredient, same dosage, and are required to meet the same efficacy and safety standards as branded drugs (including Cozaar). The main differences are inactive excipients and price — generics are typically 30–70% cheaper. Most patients experience no difference in therapeutic outcome. We are happy to provide a certificate of analysis from the manufacturer on request.',
            ]);
        }

        // ─── Ticket 19 – Post-Surgical Pain (guest / support1) ───────────────
        $t = Ticket::where('subject', 'Post-Surgical Pain Management')->first();
        if ($t) {
            Comment::create([
                'ticket_id' => $t->id,
                'user_id'   => $support1?->id,
                'content'   => 'Hello Obiageli, your concern about Tramadol dependency is valid and worth discussing with your surgeon. For mild-to-moderate post-surgical pain, alternatives include: Paracetamol (1g every 6 hours), Ibuprofen (if no contraindications), or a combination of both — known as multimodal analgesia — which can be very effective. For stronger pain, your surgeon may consider co-codamol or diclofenac suppositories. Please do not stop Tramadol abruptly; taper it gradually under medical supervision to avoid withdrawal symptoms. We can provide any of the alternatives once you have a prescription.',
            ]);
        }

        // ─── Ticket 20 – Blood Pressure Monitor (guest / support2) ──────────
        $t = Ticket::where('subject', 'Blood Pressure Monitor Recommendation')->first();
        if ($t) {
            Comment::create([
                'ticket_id' => $t->id,
                'user_id'   => $support2?->id,
                'content'   => 'Hello Emmanuel, we currently stock the Omron HEM-7120 and the A&D Medical UA-611 — both are validated upper-arm digital monitors suitable for home use and are NAFDAC-approved. The Omron HEM-7120 (₦18,500) is our most popular model; it stores 30 readings and has an irregular heartbeat indicator. The A&D Medical model (₦22,000) offers dual-user memory. Both come with a 2-year warranty. Would you like to add one to your next delivery or visit us in-store?',
            ]);
        }
    }
}
