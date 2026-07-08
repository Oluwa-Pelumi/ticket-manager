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
            ['email' => 'admin@laradocs.test'],
            [
                'name'             => 'Admin User',
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
                'name'             => 'Sarah Okonkwo',
                'role'             => 'support',
                'password'         => Hash::make('password'),
                'phone_number'  => '+2348000000002',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'support2@laradocs.test'],
            [
                'name'             => 'Daniel Eze',
                'role'             => 'support',
                'password'         => Hash::make('password'),
                'phone_number'  => '+2348000000003',
                'email_verified_at' => now(),
            ]
        );

        // ── Regular users ────────────────────────────────────────────────────
        User::updateOrCreate(
            ['email' => 'user1@laradocs.test'],
            [
                'name'             => 'Emeka Nwosu',
                'role'             => 'user',
                'password'         => Hash::make('password'),
                'phone_number'  => '+2348000000004',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'user2@laradocs.test'],
            [
                'name'             => 'Fatima Aliyu',
                'role'             => 'user',
                'password'         => Hash::make('password'),
                'phone_number'  => '+2348000000005',
                'email_verified_at' => now(),
            ]
        );
    }
}
