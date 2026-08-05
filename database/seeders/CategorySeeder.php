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
            ['name' => 'Order'],
            ['name' => 'Enquiry'],
            ['name' => 'Consultation'],
        ];

        foreach ($categories as $cat) {
            $slug = Str::slug($cat['name']);
            
            $category = Category::updateOrCreate(
                ['slug' => $slug],
                ['name' => $cat['name']]
            );

            Ticket::where('subject', $slug)->update(['category_id' => $category->id]);
        }
    }
}
