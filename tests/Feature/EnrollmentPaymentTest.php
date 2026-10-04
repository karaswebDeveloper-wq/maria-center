<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Livewire\EnrollmentPayments;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class EnrollmentPaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_record_a_payment(): void
    {
        $enrollment = Enrollment::factory()->create(['fee_amount' => 140, 'support_amount' => 40]);

        Livewire::actingAs(User::factory()->create())
            ->test(EnrollmentPayments::class, ['enrollment' => $enrollment])
            ->set('amount', 100)
            ->set('paymentDate', now()->format('Y-m-d'))
            ->set('paymentMethod', 'cash')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('payments', ['enrollment_id' => $enrollment->id, 'amount' => 100]);
    }

    public function test_overpayment_is_rejected_with_arabic_message(): void
    {
        $enrollment = Enrollment::factory()->create(['fee_amount' => 100, 'support_amount' => 0]);

        Livewire::actingAs(User::factory()->create())
            ->test(EnrollmentPayments::class, ['enrollment' => $enrollment])
            ->set('amount', 500)
            ->set('paymentDate', now()->format('Y-m-d'))
            ->set('paymentMethod', 'cash')
            ->call('save')
            ->assertHasErrors('amount');
    }
}
