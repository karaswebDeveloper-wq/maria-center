<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Livewire\PayrollShow;
use App\Livewire\PayrollsIndex;
use App\Models\AcademicPeriod;
use App\Models\Enrollment;
use App\Models\TeacherPayroll;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PayrollManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_generate_payroll_from_index(): void
    {
        $period = AcademicPeriod::factory()->create();
        Enrollment::factory()->create(['period_id' => $period->id, 'status' => 'active']);

        Livewire::actingAs(User::factory()->create())
            ->test(PayrollsIndex::class)
            ->set('periodId', $period->id)
            ->call('generate')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('teacher_payrolls', ['period_id' => $period->id, 'status' => 'draft']);
    }

    public function test_admin_can_approve_and_pay_from_show_page(): void
    {
        $period = AcademicPeriod::factory()->create();
        Enrollment::factory()->create(['period_id' => $period->id, 'status' => 'active']);

        $payroll = app(\App\Services\PayrollService::class)->generate($period, User::factory()->create());

        Livewire::actingAs(User::factory()->create())
            ->test(PayrollShow::class, ['payroll' => $payroll])
            ->call('approve')
            ->assertSee('معتمد');

        Livewire::actingAs(User::factory()->create())
            ->test(PayrollShow::class, ['payroll' => $payroll->fresh()])
            ->call('markAsPaid')
            ->assertSee('مصروف');
    }
}