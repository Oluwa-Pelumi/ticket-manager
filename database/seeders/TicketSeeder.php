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
        $user1 = User::where('email', 'user1@laradrug.test')->first();
        $user2 = User::where('email', 'user2@laradrug.test')->first();

        $support1 = User::where('email', 'support1@laradrug.test')->first();
        $support2 = User::where('email', 'support2@laradrug.test')->first();

        $order   = Category::where('slug', 'order')->first();
        $enquiry = Category::where('slug', 'enquiry')->first();
        $consult = Category::where('slug', 'consultation')->first();

        // Fallback if categories haven't been seeded yet
        $catIds = array_filter([
            $order?->id,
            $enquiry?->id,
            $consult?->id,
        ]);

        $tickets = [
            // ── Emeka's tickets (user1) ──────────────────────────────────────
            [
                'priority'          => 'high',
                'subject'           => 'order',
                'recurrence_period' => 'monthly',
                'user_id'           => $user1?->id,
                'category_id'       => $order?->id,
                'order_type'        => 'recurrent',
                'name'              => 'Emeka Nwosu',
                'status'            => 'in-progress',
                'whatsapp_number'   => '+2348000000004',
                'email'             => 'user1@laradrug.test',
                'attended_to_by'    => [$support1?->id, $support2?->id],
                'content'           => 'I need a refill for my Metformin 500mg. My last prescription was filled three weeks ago and I am running low. Please process this as soon as possible.',
            ],
            [
                'priority'        => 'medium',
                'status'          => 'closed',
                'user_id'         => $user1?->id,
                'name'            => 'Emeka Nwosu',
                'category_id'     => $enquiry?->id,
                'subject'         => 'consultation',
                'whatsapp_number' => '+2348000000004',
                'attended_to_by'  => [$support2?->id],
                'email'           => 'user1@laradrug.test',
                'content'         => 'My doctor recently increased my Lisinopril dosage from 5mg to 10mg. I wanted to confirm whether I should take the new dose in the morning or at night.',
            ],
            [
                'priority'        => 'high',
                'status'          => 'open',
                'user_id'         => $user1?->id,
                'name'            => 'Emeka Nwosu',
                'category_id'     => $consult?->id,
                'subject'         => 'consultation',
                'attended_to_by'  => $support2?->id,
                'whatsapp_number' => '+2348000000004',
                'email'           => 'user1@laradrug.test',
                'content'         => 'I was recently prescribed Atorvastatin 20mg in addition to my existing medications. Can I take it safely alongside Lisinopril and Metformin?',
            ],
            [
                'subject'         => 'order',
                'priority'        => 'medium',
                'recurrence_period' => 'weekly',
                'user_id'         => $user1?->id,
                'category_id'     => $order?->id,
                'order_type'      => 'recurrent',
                'name'            => 'Emeka Nwosu',
                'status'          => 'in-progress',
                'attended_to_by'  => $support1?->id,
                'whatsapp_number' => '+2348000000004',
                'email'           => 'user1@laradrug.test',
                'content'         => 'Please set up a order weekly order for Amlodipine 5mg tablets. I have been prescribed this for long-term blood pressure management.',
            ],
            [
                'priority'        => 'low',
                'status'          => 'closed',
                'user_id'         => $user1?->id,
                'name'            => 'Emeka Nwosu',
                'category_id'     => $enquiry?->id,
                'subject'         => 'consultation',
                'attended_to_by'  => $support2?->id,
                'whatsapp_number' => '+2348000000004',
                'email'           => 'user1@laradrug.test',
                'content'         => 'My order from last week has not arrived yet. Could you please check on the delivery status and give me an update?',
            ],

            // ── Fatima's tickets (user2) ─────────────────────────────────────
            [
                'priority'        => 'high',
                'status'          => 'open',
                'user_id'         => $user2?->id,
                'category_id'     => $consult?->id,
                'name'            => 'Fatima Aliyu',
                'subject'         => 'consultation',
                'attended_to_by'  => $support2?->id,
                'whatsapp_number' => '+2348000000005',
                'email'           => 'user2@laradrug.test',
                'content'         => 'I have a dental infection and my dentist recommended Amoxicillin. Do you have it in stock and can I get it without a new written prescription?',
            ],
            [
                'priority'          => 'low',
                'subject'           => 'order',
                'recurrence_period' => 'monthly',
                'user_id'           => $user2?->id,
                'category_id'       => $order?->id,
                'order_type'        => 'recurrent',
                'status'            => 'in-progress',
                'name'              => 'Fatima Aliyu',
                'attended_to_by'    => $support1?->id,
                'whatsapp_number'   => '+2348000000005',
                'email'             => 'user2@laradrug.test',
                'content'           => 'I would like to place a bulk order of Paracetamol 500mg for my household. We go through about 60 tablets a month. Can you set up a monthly delivery?',
            ],
            [
                'priority'        => 'medium',
                'status'          => 'closed',
                'subject'         => 'enquiry',
                'user_id'         => $user2?->id,
                'category_id'     => $enquiry?->id,
                'name'            => 'Fatima Aliyu',
                'attended_to_by'  => $support2?->id,
                'whatsapp_number' => '+2348000000005',
                'email'           => 'user2@laradrug.test',
                'content'         => 'I have recently been prescribed insulin and I am concerned about proper storage, especially during power outages. What are the best practices?',
            ],
            [
                'priority'        => 'high',
                'subject'         => 'enquiry',
                'user_id'         => $user2?->id,
                'category_id'     => $consult?->id,
                'status'          => 'in-progress',
                'name'            => 'Fatima Aliyu',
                'attended_to_by'  => $support1?->id,
                'whatsapp_number' => '+2348000000005',
                'email'           => 'user2@laradrug.test',
                'content'         => 'I am 12 weeks pregnant and experiencing back pain. I know I cannot take ibuprofen. What pain relief options are safe for me to use?',
            ],
            [
                'status'            => 'open',
                'subject'           => 'order',
                'priority'          => 'medium',
                'user_id'           => $user2?->id,
                'category_id'       => $order?->id,
                'order_type'        => 'recurrent',
                'recurrence_period' => 'two-weeks',
                'name'              => 'Fatima Aliyu',
                'attended_to_by'    => $support2?->id,
                'whatsapp_number'   => '+2348000000005',
                'email'             => 'user2@laradrug.test',
                'content'           => 'My doctor prescribed ferrous sulphate 200mg for anaemia. I need a steady supply. Please set up a two-week order order for me.',
            ],

            // ── Guest / walk-in tickets (no user_id) ─────────────────────────
            [
                'user_id'         => null,
                'status'          => 'open',
                'priority'        => 'medium',
                'subject'         => 'enquiry',
                'category_id'     => $enquiry?->id,
                'attended_to_by'  => $support2?->id,
                'whatsapp_number' => '+2348011122233',
                'name'            => 'Chukwuemeka Adeyemi',
                'email'           => 'c.adeyemi@example.com',
                'content'         => 'Do you currently stock Ciprofibrate 100mg? I was prescribed this for elevated triglycerides and my local pharmacy does not carry it.',
            ],
            [
                'user_id'         => null,
                'priority'        => 'high',
                'subject'         => 'enquiry',
                'name'            => 'Ngozi Obi',
                'category_id'     => $order?->id,
                'status'          => 'in-progress',
                'attended_to_by'  => $support2?->id,
                'whatsapp_number' => '+2348022233344',
                'email'           => 'ngozi.obi@example.com',
                'content'         => 'I require Insulin Glargine 100 units/ml urgently. My supply has run out and I cannot reach my usual supplier. Can you fulfil this today?',
            ],
            [
                'user_id'         => null,
                'priority'        => 'high',
                'status'          => 'closed',
                'subject'         => 'enquiry',
                'name'            => 'Bello Musa',
                'category_id'     => $consult?->id,
                'attended_to_by'  => $support1?->id,
                'whatsapp_number' => '+2348033344455',
                'email'           => 'bello.musa@example.com',
                'content'         => 'I am on Warfarin and a family member suggested I try a local herbal tea for energy. Is this safe? I am worried about interactions affecting my INR.',
            ],
            [
                'user_id'         => null,
                'priority'        => 'low',
                'status'          => 'closed',
                'subject'         => 'enquiry',
                'category_id'     => $enquiry?->id,
                'attended_to_by'  => $support2?->id,
                'name'            => 'Adaora Chukwu',
                'whatsapp_number' => '+2348044455566',
                'email'           => 'adaora.chukwu@example.com',
                'content'         => 'My prescription is dated three months ago. Is it still valid for dispensing, or do I need to see my doctor again for a new one?',
            ],
            [
                'user_id'           => null,
                'priority'          => 'low',
                'subject'           => 'order',
                'category_id'       => $order?->id,
                'order_type'        => 'recurrent',
                'recurrence_period' => 'quarterly',
                'status'            => 'in-progress',
                'attended_to_by'    => $support1?->id,
                'name'              => 'Ibrahim Yusuf',
                'whatsapp_number'   => '+2348055566677',
                'email'             => 'ibrahim.yusuf@example.com',
                'content'           => 'I take Omeprazole 20mg daily for GERD. I would like to order a three-month supply at once to avoid running out. Is that possible?',
            ],
            [
                'user_id'         => null,
                'status'          => 'open',
                'priority'        => 'medium',
                'subject'         => 'enquiry',
                'category_id'     => $consult?->id,
                'name'            => 'Chiamaka Eze',
                'attended_to_by'  => $support2?->id,
                'whatsapp_number' => '+2348066677788',
                'email'           => 'chiamaka.eze@example.com',
                'content'         => 'I have been taking antibiotics frequently over the past year. I am concerned I may be developing resistance. How can I manage this going forward?',
            ],
            [
                'user_id'         => null,
                'priority'        => 'low',
                'status'          => 'closed',
                'subject'         => 'enquiry',
                'category_id'     => $enquiry?->id,
                'attended_to_by'  => $support2?->id,
                'name'            => 'Tunde Fashola',
                'whatsapp_number' => '+2348077788899',
                'email'           => 'tunde.fashola@example.com',
                'content'         => 'My insurance covers only generic medications. Can you confirm whether you carry generic Losartan and if the efficacy is the same as the branded Cozaar?',
            ],
            [
                'user_id'           => null,
                'status'            => 'open',
                'subject'           => 'order',
                'priority'          => 'medium',
                'recurrence_period' => 'monthly',
                'category_id'       => $order?->id,
                'order_type'        => 'recurrent',
                'attended_to_by'    => $support2?->id,
                'whatsapp_number'   => '+2348088899900',
                'name'              => 'Yetunde Adeyemi',
                'email'             => 'yetunde.adeyemi@example.com',
                'content'           => 'My paediatrician has prescribed Vitamin D drops for my 6-month-old. I would like a monthly standing order so I never miss a dose.',
            ],
            [
                'user_id'         => null,
                'priority'        => 'high',
                'subject'         => 'enquiry',
                'category_id'     => $consult?->id,
                'status'          => 'in-progress',
                'attended_to_by'  => $support1?->id,
                'whatsapp_number' => '+2348099900011',
                'name'            => 'Obiageli Nwachukwu',
                'email'           => 'obiageli.nw@example.com',
                'content'         => 'I recently had a laparoscopy and was sent home with Tramadol. I am concerned about dependency. What are the safer alternatives I can discuss with my surgeon?',
            ],
            [
                'user_id'         => null,
                'priority'        => 'low',
                'status'          => 'closed',
                'subject'         => 'enquiry',
                'category_id'     => $enquiry?->id,
                'attended_to_by'  => $support2?->id,
                'whatsapp_number' => '+2348011100022',
                'name'            => 'Emmanuel Okafor',
                'email'           => 'emmanuel.okafor@example.com',
                'content'         => 'I was told to monitor my blood pressure at home. Can you recommend a reliable, affordable automatic blood pressure monitor that I could purchase from you?',
            ],
        ];

        foreach ($tickets as $data) {
            Ticket::create($data);
        }
    }
}
