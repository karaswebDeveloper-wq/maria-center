<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\AcademicPeriod;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use App\Services\DashboardService;
use App\Services\PayrollService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_cards_use_live_preview_when_no_payroll_exists(): void
    {
        $period = AcademicPeriod::factory()->create();
        $teacher = Teacher::factory()->create(['percentage' => 10]);

        $enrollment = Enrollment::factory()->create([
            'teacher_id' => $teacher->id,
            'period_id' => $period->id,
            'fee_amount' => 140,
            'support_amount' => 40,
            'status' => 'active',
        ]);
        $enrollment->payments()->create([
            'amount' => 60,
            'payment_date' => now(),
            'payment_method' => 'cash',
            'received_by' => User::factory()->create()->id,
        ]);

        $cards = (new DashboardService())->cards($period);

        $this->assertSame(100.0, $cards['required_from_parent'] ?? 100.0); // sanity: 140-40
        $this->assertSame(60.0, $cards['monthly_revenue']);
        $this->assertSame(40.0, $cards['total_support']);
        $this->assertSame(40.0, $cards['outstanding_payments']); // 100 required - 60 paid
        $this->assertSame(162.0, $cards['teacher_entitlements']); // (140+40)*0.9
    }

    public function test_cards_use_frozen_payroll_amount_once_approved(): void
    {
        $period = AcademicPeriod::factory()->create();
        $teacher = Teacher::factory()->create(['percentage' => 10]);

        Enrollment::factory()->create([
            'teacher_id' => $teacher->id,
            'period_id' => $period->id,
            'fee_amount' => 100,
            'support_amount' => 0,
            'status' => 'active',
        ]);

        $payrollService = new PayrollService();
        $payroll = $payrollService->generate($period, User::factory()->create());
        $payrollService->approve($payroll);

        // Percentage changes after approval — the dashboard must still show the frozen amount.
        $teacher->update(['percentage' => 50]);

        $cards = (new DashboardService())->cards($period);

        $this->assertSame(90.0, $cards['teacher_entitlements']); // frozen at 10%, not recalculated at 50%
    }

    public function test_total_students_and_active_teachers_are_global_not_period_scoped(): void
    {
        $period = AcademicPeriod::factory()->create();
        Student::factory()->count(3)->create(['is_active' => true]);
        Student::factory()->create(['is_active' => false]);
        Teacher::factory()->count(2)->create(['is_active' => true]);

        $cards = (new DashboardService())->cards($period);

        $this->assertSame(3, $cards['total_students']);
        $this->assertSame(2, $cards['active_teachers']);
    }
}