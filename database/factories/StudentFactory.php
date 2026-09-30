<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Family;
use App\Models\Grade;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'family_id' => Family::factory(),
            'grade_id' => Grade::factory(),
            'student_code' => 'STU-'.fake()->unique()->numerify('#####'),
            'name' => fake('ar_SA')->name(),
            'phone' => fake()->numerify('01#########'),
            'is_active' => true,
        ];
    }
}