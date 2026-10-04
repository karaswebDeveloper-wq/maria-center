<?php

declare(strict_types=1);

namespace Tests\Unit\Reports;

use App\Models\AcademicPeriod;
use App\Models\Enrollment;
use App\Models\Teacher;
use App\Services\Reports\TeacherPayrollReportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeacherPayrollReportServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_matches_the_spec_example_without_persisting_anything(): void
    {
        $period = AcademicPeriod::factory()->create();
        $teacher = Teacher::factory()->create(['percentage' => 10]);

        Enrollment::factory()->create([
            'teacher_id' => $teacher->id,
            'period_id' => $period->id,
            'fee_amount' => 140,
            'support_amount' => 40,
            'status' => 'active',
        ]);

        $rows = (new TeacherPayrollReportService())->get($period);

        $this->assertCount(1, $rows);
        $this->assertSame(180.0, $rows->first()->gross_amount);
        $this->assertSame(18.0, $rows->first()->center_amount);
        $this->assertSame(162.0, $rows->first()->teacher_amount);
        $this->assertDatabaseCount('teacher_payrolls', 0);
        $this->assertDatabaseCount('teacher_payroll_items', 0);
    }
}