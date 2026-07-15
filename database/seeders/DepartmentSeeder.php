<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name' => 'Academic Division', 'faculty_id' => 3],
            ['name' => 'Anaesthesia', 'faculty_id' => 6],
            ['name' => 'Anatomy', 'faculty_id' => 5],
            ['name' => 'Biochemistry', 'faculty_id' => 5],
            ['name' => 'Biomedical Communication Centre', 'faculty_id' => 1],
            ['name' => 'Biomedical Laboratory Science', 'faculty_id' => 5],
            ['name' => 'Central Animal House', 'faculty_id' => 2],
            ['name' => 'Chemical Pathology', 'faculty_id' => 4],
            ['name' => 'Child Oral Health', 'faculty_id' => 7],
            ['name' => 'College Internalization Office & College Curriculum Committee', 'faculty_id' => 14],
            ['name' => 'College Medical Education Unit (CMEU)', 'faculty_id' => 3],
            ['name' => 'College Office', 'faculty_id' => 3],
            ['name' => 'College Research & Innovation Management', 'faculty_id' => 15],
            ['name' => 'Community Medicine', 'faculty_id' => 6],
            ['name' => 'Corporate Affairs', 'faculty_id' => 16],
            ['name' => 'Correspondence', 'faculty_id' => 3],
            ['name' => 'Counselling Unit', 'faculty_id' => 17],
            ['name' => 'Deputy Provost\'s Office', 'faculty_id' => 14],
            ['name' => 'Environmental Health Sciences', 'faculty_id' => 9],
            ['name' => 'Epidemiology & Medical Statistics', 'faculty_id' => 9],
            ['name' => 'Basic Clinical Sciences', 'faculty_id' => 6],
            ['name' => 'Basic Medical Sciences', 'faculty_id' => 5],
            ['name' => 'Clinical Sciences', 'faculty_id' => 6],
            ['name' => 'Dentistry', 'faculty_id' => 7],
            ['name' => 'Public Health', 'faculty_id' => 9],
            ['name' => 'Finance', 'faculty_id' => 10],
            ['name' => 'General Services', 'faculty_id' => 3],
            ['name' => 'Haematology', 'faculty_id' => 4],
            ['name' => 'Health Policy & Management', 'faculty_id' => 9],
            ['name' => 'Health Promotion & Education', 'faculty_id' => 9],
            ['name' => 'HR&D - General Office (Academic)', 'faculty_id' => 3],
            ['name' => 'HR&D - General Office (Non-Academic)', 'faculty_id' => 3],
            ['name' => 'HR&D - PAR\'s Office', 'faculty_id' => 3],
            ['name' => 'HR&D - SAR\'s Office', 'faculty_id' => 3],
            ['name' => 'Human Nutrition', 'faculty_id' => 9],
            ['name' => 'IAMRAT', 'faculty_id' => 11],
            ['name' => 'Ibarapa Community & Primary Care', 'faculty_id' => 12],
            ['name' => 'Immunology', 'faculty_id' => 5],
            ['name' => 'Infectious Diseases Institute (IDI)', 'faculty_id' => 11],
            ['name' => 'Information Technology Unit (ITU)', 'faculty_id' => 14],
            ['name' => 'Institute of Cardio-Vascular Diseases', 'faculty_id' => 11],
            ['name' => 'Institute of Child Health', 'faculty_id' => 11],
            ['name' => 'Internal Audit', 'faculty_id' => 14],
            ['name' => 'Kitchen', 'faculty_id' => 14],
            ['name' => 'Medical Library', 'faculty_id' => 13],
            ['name' => 'Medical Microbiology & Parasitology', 'faculty_id' => 4],
            ['name' => 'Medicine', 'faculty_id' => 6],
            ['name' => 'Nuclear Medicine', 'faculty_id' => 6],
            ['name' => 'Nursing', 'faculty_id' => 8],
            ['name' => 'O.R.L.', 'faculty_id' => 6],
            ['name' => 'Obstetrics And Gynaecology', 'faculty_id' => 6],
            ['name' => 'Office of Provost (Alumni Office)', 'faculty_id' => 14],
            ['name' => 'Opthalmology', 'faculty_id' => 6],
            ['name' => 'Oral & Max. Surgery', 'faculty_id' => 7],
            ['name' => 'Oral Pathology', 'faculty_id' => 7],
            ['name' => 'Paediatrics', 'faculty_id' => 6],
            ['name' => 'Pathology', 'faculty_id' => 6],
            ['name' => 'Periodontology & Community Dentistry', 'faculty_id' => 7],
            ['name' => 'Pharmacology & Therapeutics', 'faculty_id' => 4],
            ['name' => 'Physiology', 'faculty_id' => 5],
            ['name' => 'Physiotherapy', 'faculty_id' => 6],
            ['name' => 'Provost Office', 'faculty_id' => 14],
            ['name' => 'Psychiatry', 'faculty_id' => 6],
            ['name' => 'Radiation Oncology', 'faculty_id' => 6],
            ['name' => 'Radiology', 'faculty_id' => 6],
            ['name' => 'Restorative Dentistry', 'faculty_id' => 7],
            ['name' => 'Secretary to the College\'s Office', 'faculty_id' => 3],
            ['name' => 'Surgery', 'faculty_id' => 6],
            ['name' => 'Transport Office', 'faculty_id' => 3],
            ['name' => 'Virology', 'faculty_id' => 5],
        ];

        $departments = array_map(function ($department) {
            $department['slug'] = Str::slug($department['name']);
            return $department;
        }, $departments);

        DB::table('departments')->insert($departments);
    }
}
