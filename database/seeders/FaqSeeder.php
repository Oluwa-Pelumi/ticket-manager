<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Faq::create([
            'question' => 'How do I create a support ticket?',
            'answer'   => 'Click "Create Ticket", fill in your contact details, select the support category, describe your issue clearly, and submit.',
            'order'    => 1,
        ]);

        \App\Models\Faq::create([
            'question' => 'Can I check ticket status without logging in?',
            'answer'   => 'Yes. Use the "View Ticket" option and search with the same email address used when the ticket was submitted.',
            'order'    => 2,
        ]);

        \App\Models\Faq::create([
            'question' => 'What should I include in my ticket description?',
            'answer'   => 'Include medication name, dosage, when the issue happened, what you expected, and what happened instead. Add images when relevant.',
            'order'    => 3,
        ]);

        \App\Models\Faq::create([
            'question' => 'How do updates and replies work?',
            'answer'   => 'Our support/admin team responds in the ticket conversation thread. You can return to the ticket page and continue the discussion.',
            'order'    => 4,
        ]);

        \App\Models\Faq::create([
            'question' => 'Can I edit or add more information after submission?',
            'answer'   => 'Yes. Open your ticket to add comments and attachments. If the ticket is still open, you can provide additional context for faster resolution.',
            'order'    => 5,
        ]);
    }
}
