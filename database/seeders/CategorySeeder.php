<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Ticket;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // Prescription Issues
            ['name' => 'Refill request', 'slug' => 'refill_request', 'group' => 'Prescription Issues'],
            ['name' => 'Missed dose — guidance needed', 'slug' => 'missed_dose', 'group' => 'Prescription Issues'],
            ['name' => 'Wrong medication dispensed', 'slug' => 'wrong_medication', 'group' => 'Prescription Issues'],
            ['name' => 'Suspected allergic reaction', 'slug' => 'allergic_reaction', 'group' => 'Prescription Issues'],
            ['name' => 'Wrong dosage or strength on label', 'slug' => 'wrong_dosage', 'group' => 'Prescription Issues'],
            ['name' => 'Experiencing unexpected side effects', 'slug' => 'side_effects', 'group' => 'Prescription Issues'],
            
            // Consultation & Guidance
            ['name' => 'Request to speak with a pharmacist', 'slug' => 'speak_with_pharmacist', 'group' => 'Consultation & Guidance'],
            ['name' => 'Medication running out before next appointment', 'slug' => 'running_out', 'group' => 'Consultation & Guidance'],
            ['name' => 'Prescription expired or needs renewal', 'slug' => 'prescription_expired', 'group' => 'Consultation & Guidance'],
            ['name' => 'Drug interactions with food or supplements', 'slug' => 'drug_interactions', 'group' => 'Consultation & Guidance'],
            ['name' => 'Questions about storage or handling', 'slug' => 'storage_handling_questions', 'group' => 'Consultation & Guidance'],
            ['name' => 'Drug interaction concern (with another medication)', 'slug' => 'drug_interaction', 'group' => 'Consultation & Guidance'],
            ['name' => 'Transfer of prescription from another facility', 'slug' => 'transfer_prescription', 'group' => 'Consultation & Guidance'],
            ['name' => 'Unclear instructions on how to take the medication', 'slug' => 'unclear_instructions', 'group' => 'Consultation & Guidance'],
            ['name' => 'Difficulty using the drug form (inhaler, injection, patch)', 'slug' => 'difficulty_using_drug_form', 'group' => 'Consultation & Guidance'],
        ];

        foreach ($categories as $cat) {
            $category = Category::updateOrCreate(
                ['slug' => $cat['slug']],
                ['name' => $cat['name'], 'group' => $cat['group']]
            );

            // Link existing tickets that match this slug in their subject field
            Ticket::where('subject', $cat['slug'])->update(['category_id' => $category->id]);
        }
    }
}
