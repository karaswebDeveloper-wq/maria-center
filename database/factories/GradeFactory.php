<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Stage;
use Illuminate\Database\Eloquent\Factories\Factory;

class GradeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'stage_id' => Stage::factory(),
            'name' => fake('ar_SA')->word(),
            'code' => (string) fake()->unique()->numberBetween(1, 1000),
            'sort_order' => fake()->numberBetween(1, 10),
            'is_active' => true,
        ];
    }
}