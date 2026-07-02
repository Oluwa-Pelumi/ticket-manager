<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin ────────────────────────────────────────────────────────────
        User::updateOrCreate(
            ['email' => 'admin@laradrug.test'],
            [
                'name'             => 'Admin User',
                'role'             => 'admin',
                'password'         => Hash::make('password'),
                'whatsapp_number'  => '+2348000000001',
                'email_verified_at' => now(),
            ]
        );

        // ── Support staff ────────────────────────────────────────────────────
        User::updateOrCreate(
            ['email' => 'support1@laradrug.test'],
            [
                'name'             => 'Sarah Okonkwo',
                'role'             => 'support',
                'password'         => Hash::make('password'),
                'whatsapp_number'  => '+2348000000002',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'support2@laradrug.test'],
            [
                'name'             => 'Daniel Eze',
                'role'             => 'support',
                'password'         => Hash::make('password'),
                'whatsapp_number'  => '+2348000000003',
                'email_verified_at' => now(),
            ]
        );

        // ── Regular users ────────────────────────────────────────────────────
        User::updateOrCreate(
            ['email' => 'user1@laradrug.test'],
            [
                'name'             => 'Emeka Nwosu',
                'role'             => 'user',
                'password'         => Hash::make('password'),
                'whatsapp_number'  => '+2348000000004',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'user2@laradrug.test'],
            [
                'name'             => 'Fatima Aliyu',
                'role'             => 'user',
                'password'         => Hash::make('password'),
                'whatsapp_number'  => '+2348000000005',
                'email_verified_at' => now(),
            ]
        );
    }
}
