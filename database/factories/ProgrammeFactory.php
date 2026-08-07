<?php

namespace Database\Factories;

use App\Models\Programme;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Programme>
 */
class ProgrammeFactory extends Factory
{
    protected $model = Programme::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);
        return [
            'name' => ucwords($name),
            'slug' => Str::slug($name),
        ];
    }
}
