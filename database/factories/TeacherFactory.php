<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TeacherFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake('ar_SA')->name(),
            'phone' => fake()->numerify('01#########'),
            'email' => fake()->safeEmail(),
            'percentage' => fake()->randomFloat(2, 5, 20),
            'is_active' => true,
        ];
    }
}