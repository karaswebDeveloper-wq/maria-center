<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake('ar_SA')->word(),
            'code' => fake()->unique()->slug(2),
            'is_active' => true,
        ];
    }
}