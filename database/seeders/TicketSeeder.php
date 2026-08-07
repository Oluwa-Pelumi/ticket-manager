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
        $user1   = User::where('email', 'user1@laradrug.test')->first();
        $user2   = User::where('email', 'user2@laradrug.test')->first();

        $support1 = User::where('email', 'support1@laradrug.test')->first();
        $support2 = User::where('email', 'support2@laradrug.test')->first();

        $order       = Category::where('slug', 'order')->first();
        $enquiry     = Category::where('slug', 'enquiry')->first();
        $consult     = Category::where('slug', 'consultation')->first();

        // Fallback if categories haven't been seeded yet
        $catIds = array_filter([
            $order?->id,
            $enquiry?->id,
            $consult?->id,
        ]);

        $tickets = [
            // ── Emeka's tickets (user1) ──────────────────────────────────────
            [
                'user_id'         => $user1?->id,
                'name'            => 'Emeka Nwosu',
                'email'           => 'user1@laradrug.test',
                'whatsapp_number' => '+2348000000004',
                'category_id'     => $order?->id,
                'subject'         => 'order',
                'content'         => 'I need a refill for my Metformin 500mg. My last prescription was filled three weeks ago and I am running low. Please process this as soon as possible.',
                'priority'        => 'high',
                'status'          => 'in-progress',
                'attended_to_by'  => [$support1?->id, $support2?->id],
                'order_type'      => 'order',
                'recurrence_period' => 'monthly',
            ],
            [
                'user_id'         => $user1?->id,
                'name'            => 'Emeka Nwosu',
                'email'           => 'user1@laradrug.test',
                'whatsapp_number' => '+2348000000004',
                'category_id'     => $enquiry?->id,
                'subject'         => 'consultation',
                'content'         => 'My doctor recently increased my Lisinopril dosage from 5mg to 10mg. I wanted to confirm whether I should take the new dose in the morning or at night.',
                'priority'        => 'medium',
                'status'          => 'closed',
                'attended_to_by'  => [$support2?->id],
            ],
            [
                'user_id'         => $user1?->id,
                'name'            => 'Emeka Nwosu',
                'email'           => 'user1@laradrug.test',
                'whatsapp_number' => '+2348000000004',
                'category_id'     => $consult?->id,
                'subject'         => 'consultation',
                'content'         => 'I was recently prescribed Atorvastatin 20mg in addition to my existing medications. Can I take it safely alongside Lisinopril and Metformin?',
                'priority'        => 'high',
                'status'          => 'open',
                'attended_to_by'  => $support2?->id,
            ],
            [
                'user_id'         => $user1?->id,
                'name'            => 'Emeka Nwosu',
                'email'           => 'user1@laradrug.test',
                'whatsapp_number' => '+2348000000004',
                'category_id'     => $order?->id,
                'subject'         => 'order',
                'content'         => 'Please set up a order weekly order for Amlodipine 5mg tablets. I have been prescribed this for long-term blood pressure management.',
                'priority'        => 'medium',
                'status'          => 'in-progress',
                'attended_to_by'  => $support1?->id,
                'order_type'      => 'order',
                'recurrence_period' => 'weekly',
            ],
            [
                'user_id'         => $user1?->id,
                'name'            => 'Emeka Nwosu',
                'email'           => 'user1@laradrug.test',
                'whatsapp_number' => '+2348000000004',
                'category_id'     => $enquiry?->id,
                'subject'         => 'consultation',
                'content'         => 'My order from last week has not arrived yet. Could you please check on the delivery status and give me an update?',
                'priority'        => 'low',
                'status'          => 'closed',
                'attended_to_by'  => $support2?->id,
            ],

            // ── Fatima's tickets (user2) ─────────────────────────────────────
            [
                'user_id'         => $user2?->id,
                'name'            => 'Fatima Aliyu',
                'email'           => 'user2@laradrug.test',
                'whatsapp_number' => '+2348000000005',
                'category_id'     => $consult?->id,
                'subject'         => 'consultation',
                'content'         => 'I have a dental infection and my dentist recommended Amoxicillin. Do you have it in stock and can I get it without a new written prescription?',
                'priority'        => 'high',
                'status'          => 'open',
                'attended_to_by'  => $support2?->id,
            ],
            [
                'user_id'         => $user2?->id,
                'name'            => 'Fatima Aliyu',
                'email'           => 'user2@laradrug.test',
                'whatsapp_number' => '+2348000000005',
                'category_id'     => $order?->id,
                'subject'         => 'order',
                'content'         => 'I would like to place a bulk order of Paracetamol 500mg for my household. We go through about 60 tablets a month. Can you set up a monthly delivery?',
                'priority'        => 'low',
                'status'          => 'in-progress',
                'attended_to_by'  => $support1?->id,
                'order_type'      => 'order',
                'recurrence_period' => 'monthly',
            ],
            [
                'user_id'         => $user2?->id,
                'name'            => 'Fatima Aliyu',
                'email'           => 'user2@laradrug.test',
                'whatsapp_number' => '+2348000000005',
                'category_id'     => $enquiry?->id,
                'subject'         => 'enquiry',
                'content'         => 'I have recently been prescribed insulin and I am concerned about proper storage, especially during power outages. What are the best practices?',
                'priority'        => 'medium',
                'status'          => 'closed',
                'attended_to_by'  => $support2?->id,
            ],
            [
                'user_id'         => $user2?->id,
                'name'            => 'Fatima Aliyu',
                'email'           => 'user2@laradrug.test',
                'whatsapp_number' => '+2348000000005',
                'category_id'     => $consult?->id,
                'subject'         => 'enquiry',
                'content'         => 'I am 12 weeks pregnant and experiencing back pain. I know I cannot take ibuprofen. What pain relief options are safe for me to use?',
                'priority'        => 'high',
                'status'          => 'in-progress',
                'attended_to_by'  => $support1?->id,
            ],
            [
                'user_id'         => $user2?->id,
                'name'            => 'Fatima Aliyu',
                'email'           => 'user2@laradrug.test',
                'whatsapp_number' => '+2348000000005',
                'category_id'     => $order?->id,
                'subject'         => 'order',
                'content'         => 'My doctor prescribed ferrous sulphate 200mg for anaemia. I need a steady supply. Please set up a two-week order order for me.',
                'priority'        => 'medium',
                'status'          => 'open',
                'attended_to_by'  => $support2?->id,
                'order_type'      => 'order',
                'recurrence_period' => 'two-weeks',
            ],

            // ── Guest / walk-in tickets (no user_id) ─────────────────────────
            [
                'user_id'         => null,
                'name'            => 'Chukwuemeka Adeyemi',
                'email'           => 'c.adeyemi@example.com',
                'whatsapp_number' => '+2348011122233',
                'category_id'     => $enquiry?->id,
                'subject'         => 'enquiry',
                'content'         => 'Do you currently stock Ciprofibrate 100mg? I was prescribed this for elevated triglycerides and my local pharmacy does not carry it.',
                'priority'        => 'medium',
                'status'          => 'open',
                'attended_to_by'  => $support2?->id,
            ],
            [
                'user_id'         => null,
                'name'            => 'Ngozi Obi',
                'email'           => 'ngozi.obi@example.com',
                'whatsapp_number' => '+2348022233344',
                'category_id'     => $order?->id,
                'subject'         => 'enquiry',
                'content'         => 'I require Insulin Glargine 100 units/ml urgently. My supply has run out and I cannot reach my usual supplier. Can you fulfil this today?',
                'priority'        => 'high',
                'status'          => 'in-progress',
                'attended_to_by'  => $support2?->id,
            ],
            [
                'user_id'         => null,
                'name'            => 'Bello Musa',
                'email'           => 'bello.musa@example.com',
                'whatsapp_number' => '+2348033344455',
                'category_id'     => $consult?->id,
                'subject'         => 'enquiry',
                'content'         => 'I am on Warfarin and a family member suggested I try a local herbal tea for energy. Is this safe? I am worried about interactions affecting my INR.',
                'priority'        => 'high',
                'status'          => 'closed',
                'attended_to_by'  => $support1?->id,
            ],
            [
                'user_id'         => null,
                'name'            => 'Adaora Chukwu',
                'email'           => 'adaora.chukwu@example.com',
                'whatsapp_number' => '+2348044455566',
                'category_id'     => $enquiry?->id,
                'subject'         => 'enquiry',
                'content'         => 'My prescription is dated three months ago. Is it still valid for dispensing, or do I need to see my doctor again for a new one?',
                'priority'        => 'low',
                'status'          => 'closed',
                'attended_to_by'  => $support2?->id,
            ],
            [
                'user_id'         => null,
                'name'            => 'Ibrahim Yusuf',
                'email'           => 'ibrahim.yusuf@example.com',
                'whatsapp_number' => '+2348055566677',
                'category_id'     => $order?->id,
                'subject'         => 'order',
                'content'         => 'I take Omeprazole 20mg daily for GERD. I would like to order a three-month supply at once to avoid running out. Is that possible?',
                'priority'        => 'low',
                'status'          => 'in-progress',
                'attended_to_by'  => $support1?->id,
                'order_type'      => 'order',
                'recurrence_period' => 'quarterly',
            ],
            [
                'user_id'         => null,
                'name'            => 'Chiamaka Eze',
                'email'           => 'chiamaka.eze@example.com',
                'whatsapp_number' => '+2348066677788',
                'category_id'     => $consult?->id,
                'subject'         => 'enquiry',
                'content'         => 'I have been taking antibiotics frequently over the past year. I am concerned I may be developing resistance. How can I manage this going forward?',
                'priority'        => 'medium',
                'status'          => 'open',
                'attended_to_by'  => $support2?->id,
            ],
            [
                'user_id'         => null,
                'name'            => 'Tunde Fashola',
                'email'           => 'tunde.fashola@example.com',
                'whatsapp_number' => '+2348077788899',
                'category_id'     => $enquiry?->id,
                'subject'         => 'enquiry',
                'content'         => 'My insurance covers only generic medications. Can you confirm whether you carry generic Losartan and if the efficacy is the same as the branded Cozaar?',
                'priority'        => 'low',
                'status'          => 'closed',
                'attended_to_by'  => $support2?->id,
            ],
            [
                'user_id'         => null,
                'name'            => 'Yetunde Adeyemi',
                'email'           => 'yetunde.adeyemi@example.com',
                'whatsapp_number' => '+2348088899900',
                'category_id'     => $order?->id,
                'subject'         => 'order',
                'content'         => 'My paediatrician has prescribed Vitamin D drops for my 6-month-old. I would like a monthly standing order so I never miss a dose.',
                'priority'        => 'medium',
                'status'          => 'open',
                'attended_to_by'  => $support2?->id,
                'order_type'      => 'order',
                'recurrence_period' => 'monthly',
            ],
            [
                'user_id'         => null,
                'name'            => 'Obiageli Nwachukwu',
                'email'           => 'obiageli.nw@example.com',
                'whatsapp_number' => '+2348099900011',
                'category_id'     => $consult?->id,
                'subject'         => 'enquiry',
                'content'         => 'I recently had a laparoscopy and was sent home with Tramadol. I am concerned about dependency. What are the safer alternatives I can discuss with my surgeon?',
                'priority'        => 'high',
                'status'          => 'in-progress',
                'attended_to_by'  => $support1?->id,
            ],
            [
                'user_id'         => null,
                'name'            => 'Emmanuel Okafor',
                'email'           => 'emmanuel.okafor@example.com',
                'whatsapp_number' => '+2348011100022',
                'category_id'     => $enquiry?->id,
                'subject'         => 'enquiry',
                'content'         => 'I was told to monitor my blood pressure at home. Can you recommend a reliable, affordable automatic blood pressure monitor that I could purchase from you?',
                'priority'        => 'low',
                'status'          => 'closed',
                'attended_to_by'  => $support2?->id,
            ],
        ];

        foreach ($tickets as $data) {
            Ticket::create($data);
        }
    }
}
