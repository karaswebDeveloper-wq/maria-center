<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Enrollment;
use App\Models\User;
use App\Services\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use RuntimeException;
use Tests\TestCase;

class PaymentServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_records_a_payment_within_the_remaining_amount(): void
    {
        $enrollment = Enrollment::factory()->create(['fee_amount' => 140, 'support_amount' => 40]);
        $user = User::factory()->create();

        $payment = (new PaymentService())->record($enrollment, [
            'amount' => 60,
            'payment_date' => now()->format('Y-m-d'),
            'payment_method' => 'cash',
        ], $user);

        $this->assertDatabaseHas('payments', ['id' => $payment->id, 'amount' => 60]);
        $this->assertSame(40.0, $enrollment->fresh()->remaining_amount);
    }

    public function test_rejects_payment_exceeding_remaining_amount(): void
    {
        $enrollment = Enrollment::factory()->create(['fee_amount' => 100, 'support_amount' => 0]);
        $user = User::factory()->create();

        $this->expectException(RuntimeException::class);

        (new PaymentService())->record($enrollment, [
            'amount' => 150,
            'payment_date' => now()->format('Y-m-d'),
            'payment_method' => 'cash',
        ], $user);
    }

    public function test_multiple_payments_accumulate_correctly(): void
    {
        $enrollment = Enrollment::factory()->create(['fee_amount' => 120, 'support_amount' => 0]);
        $user = User::factory()->create();
        $service = new PaymentService();

        $service->record($enrollment, ['amount' => 70, 'payment_date' => now()->format('Y-m-d'), 'payment_method' => 'cash'], $user);
        $service->record($enrollment, ['amount' => 50, 'payment_date' => now()->format('Y-m-d'), 'payment_method' => 'cash'], $user);

        $this->assertSame(0.0, $enrollment->fresh()->remaining_amount);
        $this->assertDatabaseCount('payments', 2);
    }
}