<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\PaymentMethod;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'enrollment_id' => Enrollment::factory(),
            'amount' => fake()->randomFloat(2, 20, 100),
            'payment_date' => fake()->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'payment_method' => fake()->randomElement(PaymentMethod::cases())->value,
            'received_by' => User::factory(),
        ];
    }
}