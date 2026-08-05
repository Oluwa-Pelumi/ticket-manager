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
            ['name' => 'Anaesthesia'],
            ['name' => 'Anatomy'],
            ['name' => 'Biochemistry'],
            ['name' => 'Biomedical Communication Centre'],
            ['name' => 'Biomedical Laboratory Science'],
            ['name' => 'Chemical Pathology'],
            ['name' => 'Child Oral Health'],
            ['name' => 'Community Medicine'],
            ['name' => 'Environmental Health Sciences'],
            ['name' => 'Epidemiology & Medical Statistics'],
            ['name' => 'Basic Clinical Sciences'],
            ['name' => 'Basic Medical Sciences'],
            ['name' => 'Clinical Sciences'],
            ['name' => 'Dentistry'],
            ['name' => 'Public Health'],
            ['name' => 'Haematology'],
            ['name' => 'Health Policy & Management'],
            ['name' => 'Health Promotion & Education'],
            ['name' => 'Human Nutrition'],
            ['name' => 'Immunology'],
            ['name' => 'Medical Microbiology & Parasitology'],
            ['name' => 'Medicine'],
            ['name' => 'Nuclear Medicine'],
            ['name' => 'Nursing'],
            ['name' => 'O.R.L.'],
            ['name' => 'Obstetrics And Gynaecology'],
            ['name' => 'Opthalmology'],
            ['name' => 'Oral & Max. Surgery'],
            ['name' => 'Oral Pathology'],
            ['name' => 'Paediatrics'],
            ['name' => 'Pathology'],
            ['name' => 'Periodontology & Community Dentistry'],
            ['name' => 'Pharmacology & Therapeutics'],
            ['name' => 'Physiology'],
            ['name' => 'Physiotherapy'],
            ['name' => 'Psychiatry'],
            ['name' => 'Radiation Oncology'],
            ['name' => 'Radiology'],
            ['name' => 'Restorative Dentistry'],
            ['name' => 'Surgery'],
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
