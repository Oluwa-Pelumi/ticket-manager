<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FacultySeeder extends Seeder
{
    public function run(): void
    {
        $faculties = [
            ['name' => 'Biomedical Communication Centre'],
            ['name' => 'Central Animal House'],
            ['name' => 'College Office'],
            ['name' => 'Basic Clinical Sciences'],
            ['name' => 'Basic Medical Sciences'],
            ['name' => 'Clinical Sciences'],
            ['name' => 'Dentistry'],
            ['name' => 'Nursing'],
            ['name' => 'Public Health'],
            ['name' => 'Finance'],
            ['name' => 'Institute'],
            ['name' => 'Ibarapa Community & Primary Care'],
            ['name' => 'Medical Library'],
            ['name' => 'Provost Office'],
            ['name' => 'College Research & Innovation Management'],
            ['name' => 'Corporate Affairs'],
            ['name' => 'Counselling Unit'],
        ];

        $faculties = array_map(function ($faculty) {
            $faculty['slug'] = Str::slug($faculty['name']);
            return $faculty;
        }, $faculties);

        DB::table('faculties')->insert($faculties);
    }
}
