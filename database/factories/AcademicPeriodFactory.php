<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AcademicPeriodFactory extends Factory
{
    public function definition(): array
    {
        $startsAt = fake()->dateTimeBetween('-1 year', '+1 year')->modify('first day of this month');

        return [
            'year' => (int) $startsAt->format('Y'),
            'month' => (int) $startsAt->format('n'),
            'name' => $startsAt->format('F Y'),
            'starts_at' => $startsAt->format('Y-m-d'),
            'ends_at' => (clone $startsAt)->modify('last day of this month')->format('Y-m-d'),
            'is_closed' => false,
        ];
    }
}
