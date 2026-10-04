<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\DurationType;
use App\Enums\EnrollmentStatus;
use App\Models\AcademicPeriod;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

class EnrollmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'teacher_id' => Teacher::factory(),
            'subject_id' => Subject::factory(),
            'period_id' => AcademicPeriod::factory(),
            'duration_type' => fake()->randomElement(DurationType::cases())->value,
            'fee_amount' => fake()->randomFloat(2, 50, 500),
            'support_amount' => 0,
            'status' => EnrollmentStatus::Active->value,
        ];
    }
}
