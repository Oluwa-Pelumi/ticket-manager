<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin ────────────────────────────────────────────────────────────
        User::updateOrCreate(
            ['email' => 'admin@laradocs.test'],
            [
                'first_name'             => 'Admin',
                'middle_name'             => '',
                'last_name'             => 'User',
                'role'             => 'admin',
                'password'         => Hash::make('password'),
                'phone_number'  => '+2348000000001',
                'email_verified_at' => now(),
            ]
        );

        // ── Support staff ────────────────────────────────────────────────────
        User::updateOrCreate(
            ['email' => 'support1@laradocs.test'],
            [
                'first_name'             => 'Sarah',
                'middle_name'             => '',
                'last_name'             => 'Okonkwo',
                'role'             => 'support',
                'password'         => Hash::make('password'),
                'phone_number'  => '+2348000000002',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'support2@laradocs.test'],
            [
                'first_name'             => 'Daniel',
                'middle_name'             => '',
                'last_name'             => 'Eze',
                'role'             => 'support',
                'password'         => Hash::make('password'),
                'phone_number'  => '+2348000000003',
                'email_verified_at' => now(),
            ]
        );

        // ── Regular users ────────────────────────────────────────────────────
        $dept1 = Department::inRandomOrder()->first();
        User::updateOrCreate(
            ['email' => 'user1@laradocs.test'],
            [
                 'first_name'             => 'Emeka',
                'middle_name'             => '',
                'last_name'             => 'Nwosu',
                 'matric_no' => 192211,
                'role'             => 'user',
                'password'         => Hash::make('password'),
                'phone_number'  => '+2348000000004',
                'faculty_id'    => $dept1 ? $dept1->faculty_id : null,
                'department_id' => $dept1 ? $dept1->id : null,
                'email_verified_at' => now(),
            ]
        );

        $dept2 = Department::inRandomOrder()->first();
        User::updateOrCreate(
            ['email' => 'user2@laradocs.test'],
            [
                 'first_name'             => 'Fatima',
                'middle_name'             => '',
                'last_name'             => 'Aliyu',
                'matric_no' => 192210,
                'role'             => 'user',
                'password'         => Hash::make('password'),
                'phone_number'  => '+2348000000005',
                'faculty_id'    => $dept2 ? $dept2->faculty_id : null,
                'department_id' => $dept2 ? $dept2->id : null,
                'email_verified_at' => now(),
            ]
        );
    }
}
