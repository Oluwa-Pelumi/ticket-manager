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
        $syllabus       = Category::where('slug', 'syllabus-request')->first();
        $statement      = Category::where('slug', 'statement-of-result-request')->first();
        $other          = Category::where('slug', 'other')->first();

        $tickets = [
            // ── Emeka's tickets (user1) ──────────────────────────────────────
            [
                'user_id'         => $user1?->id,
                'name'            => 'Emeka Nwosu',
                'email'           => 'user1@laradocs.test',
                'phone_number' => '+2348000000004',
                'category_id'     => $transcript?->id,
                'subject'         => 'syllabus-request',
                'content'         => 'I need my official undergraduate transcript sent to the University of Lagos for my postgraduate application. My admission deadline is in two weeks. Please process this as soon as possible.',
                'priority'        => 'high',
                'status'          => 'in-progress',
                'attended_to_by'  => [$support1?->id, $support2?->id],
            ],
            [
                'user_id'         => $user1?->id,
                'name'            => 'Emeka Nwosu',
                'email'           => 'user1@laradocs.test',
                'phone_number' => '+2348000000004',
                'category_id'     => $syllabus?->id,
                'subject'         => 'syllabus-request',
                'content'         => 'I am applying for a credit transfer and need the official syllabus copy for all my third-year Computer Science courses from the 2024 academic session.',
                'priority'        => 'medium',
                'status'          => 'resolved',
                'attended_to_by'  => [$support2?->id],
            ],
            [
                'user_id'         => $user1?->id,
                'name'            => 'Emeka Nwosu',
                'email'           => 'user1@laradocs.test',
                'phone_number' => '+2348000000004',
                'category_id'     => $recommendation?->id,
                'subject'         => 'syllabus-request',
                'content'         => 'I submitted a request for a letter of recommendation from Dr. Adewale for my Commonwealth Scholarship application. Could you please confirm if the letter has been signed and uploaded?',
                'priority'        => 'high',
                'status'          => 'open',
                'attended_to_by'  => $support2?->id,
            ],
            [
                'user_id'         => $user1?->id,
                'name'            => 'Emeka Nwosu',
                'email'           => 'user1@laradocs.test',
                'phone_number' => '+2348000000004',
                'category_id'     => $certificate?->id,
                'subject'         => 'syllabus-request',
                'content'         => 'I completed my program last session and would like to request the printing and collection of my original B.Sc. certificate. Can this be dispatched via courier?',
                'priority'        => 'medium',
                'status'          => 'in-progress',
                'attended_to_by'  => $support1?->id,
            ],
            [
                'user_id'         => $user1?->id,
                'name'            => 'Emeka Nwosu',
                'email'           => 'user1@laradocs.test',
                'phone_number' => '+2348000000004',
                'category_id'     => $statement?->id,
                'subject'         => 'syllabus-request',
                'content'         => 'My statement of result was approved last Tuesday, but I have not received the digital copy via email yet. Could you please check on the status and send it?',
                'priority'        => 'low',
                'status'          => 'resolved',
                'attended_to_by'  => $support2?->id,
            ],

            // ── Fatima's tickets (user2) ─────────────────────────────────────
            [
                'user_id'         => $user2?->id,
                'name'            => 'Fatima Aliyu',
                'email'           => 'user2@laradocs.test',
                'phone_number' => '+2348000000005',
                'category_id'     => $transcript?->id,
                'subject'         => 'syllabus-request',
                'content'         => 'I need my academic transcript sent directly to World Education Services (WES) for evaluation. I have my WES reference number ready. Can I submit it without coming to the campus?',
                'priority'        => 'high',
                'status'          => 'open',
                'attended_to_by'  => $support2?->id,
            ],
            [
                'user_id'         => $user2?->id,
                'name'            => 'Fatima Aliyu',
                'email'           => 'user2@laradocs.test',
                'phone_number' => '+2348000000005',
                'category_id'     => $certificate?->id,
                'subject'         => 'other',
                'content'         => 'I am coordinating the certificate requests for a group of 5 alumni. Can we submit these in bulk and get a unified tracking reference for the courier dispatch?',
                'priority'        => 'low',
                'status'          => 'in-progress',
                'attended_to_by'  => $support1?->id,
            ],
            [
                'user_id'         => $user2?->id,
                'name'            => 'Fatima Aliyu',
                'email'           => 'user2@laradocs.test',
                'phone_number' => '+2348000000005',
                'category_id'     => $syllabus?->id,
                'subject'         => 'other',
                'content'         => 'For my professional board certification, I require the detailed syllabus for the entire Chemistry program of the 2021 graduating set. What is the standard processing time?',
                'priority'        => 'medium',
                'status'          => 'resolved',
                'attended_to_by'  => $support2?->id,
            ],
            [
                'user_id'         => $user2?->id,
                'name'            => 'Fatima Aliyu',
                'email'           => 'user2@laradocs.test',
                'phone_number' => '+2348000000005',
                'category_id'     => $recommendation?->id,
                'subject'         => 'other',
                'content'         => 'I requested an academic reference letter from the Head of Department. I need to make sure it includes the official letterhead and stamp. How can I verify this?',
                'priority'        => 'high',
                'status'          => 'in-progress',
                'attended_to_by'  => $support1?->id,
            ],
            [
                'user_id'         => $user2?->id,
                'name'            => 'Fatima Aliyu',
                'email'           => 'user2@laradocs.test',
                'phone_number' => '+2348000000005',
                'category_id'     => $statement?->id,
                'subject'         => 'other',
                'content'         => 'I need a certified true copy of my Statement of Result for a job application. Please let me know how to make the payment and obtain it.',
                'priority'        => 'medium',
                'status'          => 'open',
                'attended_to_by'  => $support2?->id,
            ],

            // ── Guest / walk-in tickets (no user_id) ─────────────────────────
            [
                'user_id'         => null,
                'name'            => 'Chukwuemeka Adeyemi',
                'email'           => 'c.adeyemi@example.com',
                'phone_number' => '+2348011122233',
                'category_id'     => $other?->id,
                'subject'         => 'other',
                'content'         => 'A prospective employer is requesting verification of my degree from 2018. Can you guide me on the process and fees involved for verification?',
                'priority'        => 'medium',
                'status'          => 'open',
                'attended_to_by'  => $support2?->id,
            ],
            [
                'user_id'         => null,
                'name'            => 'Ngozi Obi',
                'email'           => 'ngozi.obi@example.com',
                'phone_number' => '+2348022233344',
                'category_id'     => $certificate?->id,
                'subject'         => 'other',
                'content'         => 'My original B.Sc. certificate was damaged in a fire. I need an official replacement or a certified duplicate. What are the requirements for this request?',
                'priority'        => 'high',
                'status'          => 'in-progress',
                'attended_to_by'  => $support2?->id,
            ],
            [
                'user_id'         => null,
                'name'            => 'Bello Musa',
                'email'           => 'bello.musa@example.com',
                'phone_number' => '+2348033344455',
                'category_id'     => $recommendation?->id,
                'subject'         => 'other',
                'content'         => 'I am applying for graduate studies abroad and need two academic references. Can the portal automatically send the request links to my former lecturers?',
                'priority'        => 'high',
                'status'          => 'resolved',
                'attended_to_by'  => $support1?->id,
            ],
            [
                'user_id'         => null,
                'name'            => 'Adaora Chukwu',
                'email'           => 'adaora.chukwu@example.com',
                'phone_number' => '+2348044455566',
                'category_id'     => $syllabus?->id,
                'subject'         => 'other',
                'content'         => 'Does the official syllabus package provided for the 2019 Civil Engineering curriculum contain the accreditation status? I need this for my visa application.',
                'priority'        => 'low',
                'status'          => 'resolved',
                'attended_to_by'  => $support2?->id,
            ],
            [
                'user_id'         => null,
                'name'            => 'Ibrahim Yusuf',
                'email'           => 'ibrahim.yusuf@example.com',
                'phone_number' => '+2348055566677',
                'category_id'     => $statement?->id,
                'subject'         => 'other',
                'content'         => 'I need my Statement of Result urgently to present at the NYSC orientation camp next week. Can this be processed on an expedited basis?',
                'priority'        => 'low',
                'status'          => 'in-progress',
                'attended_to_by'  => $support1?->id,
            ],
            [
                'user_id'         => null,
                'name'            => 'Chiamaka Eze',
                'email'           => 'chiamaka.eze@example.com',
                'phone_number' => '+2348066677788',
                'category_id'     => $transcript?->id,
                'subject'         => 'other',
                'content'         => 'I requested my transcript to be sent to the University of Ibadan last month. The receiving institution claims they have not received it yet. Can I get a tracking ID?',
                'priority'        => 'medium',
                'status'          => 'open',
                'attended_to_by'  => $support2?->id,
            ],
            [
                'user_id'         => null,
                'name'            => 'Tunde Fashola',
                'email'           => 'tunde.fashola@example.com',
                'phone_number' => '+2348077788899',
                'category_id'     => $other?->id,
                'subject'         => 'other',
                'content'         => 'My name is misspelled on the portal. It shows "Tunde Fashola" instead of "Babatunde Fashola". I need this corrected before any document is printed.',
                'priority'        => 'low',
                'status'          => 'resolved',
                'attended_to_by'  => $support2?->id,
            ],
            [
                'user_id'         => null,
                'name'            => 'Yetunde Adeyemi',
                'email'           => 'yetunde.adeyemi@example.com',
                'phone_number' => '+2348088899900',
                'category_id'     => $certificate?->id,
                'subject'         => 'other',
                'content'         => 'I completed my PGD in 2023. I have paid all fees and have my clearance form. When can I come to collect the hardcopy certificate?',
                'priority'        => 'medium',
                'status'          => 'open',
                'attended_to_by'  => $support2?->id,
            ],
            [
                'user_id'         => null,
                'name'            => 'Obiageli Nwachukwu',
                'email'           => 'obiageli.nw@example.com',
                'phone_number' => '+2348099900011',
                'category_id'     => $recommendation?->id,
                'subject'         => 'syllabus-request',
                'content'         => 'I am applying for a highly competitive fellowship and require a recommendation from the Vice Chancellor\'s office. Who is the contact person to submit my documents to?',
                'priority'        => 'high',
                'status'          => 'in-progress',
                'attended_to_by'  => $support1?->id,
            ],
            [
                'user_id'         => null,
                'name'            => 'Emmanuel Okafor',
                'email'           => 'emmanuel.okafor@example.com',
                'phone_number' => '+2348011100022',
                'category_id'     => $syllabus?->id,
                'subject'         => 'syllabus-request',
                'content'         => 'I need a copy of the official course description booklet for the Business Administration department for the years 2015-2019. Do you have a digital version?',
                'priority'        => 'low',
                'status'          => 'resolved',
                'attended_to_by'  => $support2?->id,
            ],
        ];

        foreach ($tickets as $data) {
            Ticket::create($data);
        }
    }
}
