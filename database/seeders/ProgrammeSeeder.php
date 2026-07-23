<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProgrammeSeeder extends Seeder
{
    public function run(): void
    {
        $programmes = [
            ['name' => 'Academic Division'],
            ['name' => 'Anaesthesia'],
            ['name' => 'Anatomy'],
            ['name' => 'Biochemistry'],
            ['name' => 'Biomedical Communication Centre'],
            ['name' => 'Biomedical Laboratory Science'],
            ['name' => 'Central Animal House'],
            ['name' => 'Chemical Pathology'],
            ['name' => 'Child Oral Health'],
            ['name' => 'College Internalization Office & College Curriculum Committee'],
            ['name' => 'College Medical Education Unit (CMEU)'],
            ['name' => 'College Office'],
            ['name' => 'College Research & Innovation Management'],
            ['name' => 'Community Medicine'],
            ['name' => 'Corporate Affairs'],
            ['name' => 'Correspondence'],
            ['name' => 'Counselling Unit'],
            ['name' => 'Deputy Provost\'s Office'],
            ['name' => 'Environmental Health Sciences'],
            ['name' => 'Epidemiology & Medical Statistics'],
            ['name' => 'Basic Clinical Sciences'],
            ['name' => 'Basic Medical Sciences'],
            ['name' => 'Clinical Sciences'],
            ['name' => 'Dentistry'],
            ['name' => 'Public Health'],
            ['name' => 'Finance'],
            ['name' => 'General Services'],
            ['name' => 'Haematology'],
            ['name' => 'Health Policy & Management'],
            ['name' => 'Health Promotion & Education'],
            ['name' => 'HR&D - General Office (Academic)'],
            ['name' => 'HR&D - General Office (Non-Academic)'],
            ['name' => 'HR&D - PAR\'s Office'],
            ['name' => 'HR&D - SAR\'s Office'],
            ['name' => 'Human Nutrition'],
            ['name' => 'IAMRAT'],
            ['name' => 'Ibarapa Community & Primary Care'],
            ['name' => 'Immunology'],
            ['name' => 'Infectious Diseases Institute (IDI)'],
            ['name' => 'Information Technology Unit (ITU)'],
            ['name' => 'Institute of Cardio-Vascular Diseases'],
            ['name' => 'Institute of Child Health'],
            ['name' => 'Internal Audit'],
            ['name' => 'Kitchen'],
            ['name' => 'Medical Library'],
            ['name' => 'Medical Microbiology & Parasitology'],
            ['name' => 'Medicine'],
            ['name' => 'Nuclear Medicine'],
            ['name' => 'Nursing'],
            ['name' => 'O.R.L.'],
            ['name' => 'Obstetrics And Gynaecology'],
            ['name' => 'Office of Provost (Alumni Office)'],
            ['name' => 'Opthalmology'],
            ['name' => 'Oral & Max. Surgery'],
            ['name' => 'Oral Pathology'],
            ['name' => 'Paediatrics'],
            ['name' => 'Pathology'],
            ['name' => 'Periodontology & Community Dentistry'],
            ['name' => 'Pharmacology & Therapeutics'],
            ['name' => 'Physiology'],
            ['name' => 'Physiotherapy'],
            ['name' => 'Provost Office'],
            ['name' => 'Psychiatry'],
            ['name' => 'Radiation Oncology'],
            ['name' => 'Radiology'],
            ['name' => 'Restorative Dentistry'],
            ['name' => 'Secretary to the College\'s Office'],
            ['name' => 'Surgery'],
            ['name' => 'Transport Office'],
            ['name' => 'Virology'],
        ];

        $now = now();
        $programmes = array_map(function ($programme) use ($now) {
            $programme['slug'] = Str::slug($programme['name']);
            $programme['created_at'] = $now;
            $programme['updated_at'] = $now;
            return $programme;
        }, $programmes);

        DB::table('programmes')->insert($programmes);
    }
}
