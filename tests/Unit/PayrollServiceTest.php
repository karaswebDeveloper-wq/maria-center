<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Enums\PayrollStatus;
use App\Models\AcademicPeriod;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use App\Services\PayrollService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use RuntimeException;
use Tests\TestCase;

class PayrollServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_generate_matches_the_spec_example(): void
    {
        // fee_amount=140, support_amount=40 → gross=180, 10% → center=18, teacher=162
        $period = AcademicPeriod::factory()->create();
        $teacher = Teacher::factory()->create(['percentage' => 10]);
        $subject = Subject::factory()->create();

        Enrollment::factory()->create([
            'teacher_id' => $teacher->id,
            'subject_id' => $subject->id,
            'period_id' => $period->id,
            'student_id' => Student::factory(),
            'fee_amount' => 140,
            'support_amount' => 40,
            'status' => 'active',
        ]);

        $payroll = (new PayrollService())->generate($period, User::factory()->create());
        $item = $payroll->items()->first();

        $this->assertSame(1, $item->students_count);
        $this->assertSame('180.00', $item->gross_amount);
        $this->assertSame('18.00', $item->center_amount);
        $this->assertSame('162.00', $item->teacher_amount);
    }

    public function test_cancelled_enrollments_are_excluded(): void
    {
        $period = AcademicPeriod::factory()->create();
        $teacher = Teacher::factory()->create(['percentage' => 10]);

        Enrollment::factory()->create([
            'teacher_id' => $teacher->id,
            'period_id' => $period->id,
            'status' => 'cancelled',
            'fee_amount' => 140,
            'support_amount' => 0,
        ]);

        $payroll = (new PayrollService())->generate($period, User::factory()->create());

        $this->assertSame(0, $payroll->items()->count());
    }

    public function test_cannot_regenerate_an_approved_payroll(): void
    {
        $period = AcademicPeriod::factory()->create();
        Enrollment::factory()->create(['period_id' => $period->id, 'status' => 'active']);

        $service = new PayrollService();
        $user = User::factory()->create();
        $payroll = $service->generate($period, $user);
        $service->approve($payroll);

        $this->expectException(RuntimeException::class);
        $service->generate($period, $user);
    }

    public function test_full_status_flow(): void
    {
        $period = AcademicPeriod::factory()->create();
        Enrollment::factory()->create(['period_id' => $period->id, 'status' => 'active']);

        $service = new PayrollService();
        $payroll = $service->generate($period, User::factory()->create());

        $this->assertSame(PayrollStatus::Draft, $payroll->status);

        $payroll = $service->approve($payroll);
        $this->assertSame(PayrollStatus::Approved, $payroll->status);
        $this->assertNotNull($payroll->approved_at);

        $payroll = $service->markAsPaid($payroll);
        $this->assertSame(PayrollStatus::Paid, $payroll->status);
        $this->assertNotNull($payroll->paid_at);
    }

    public function test_cannot_approve_a_payroll_with_no_items(): void
    {
        $period = AcademicPeriod::factory()->create(); // no enrollments

        $payroll = (new PayrollService())->generate($period, User::factory()->create());

        $this->expectException(RuntimeException::class);
        (new PayrollService())->approve($payroll);
    }
}