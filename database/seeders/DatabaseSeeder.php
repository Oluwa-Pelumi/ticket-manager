<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Order matters: categories → users → tickets → comments → faqs
     */
    public function run(): void
    {
        $this->call([
            ProgrammeSeeder::class,
            CategorySeeder::class,
            UserSeeder::class,
            TicketSeeder::class,
            CommentSeeder::class,
            FaqSeeder::class,
        ]);
    }
}
