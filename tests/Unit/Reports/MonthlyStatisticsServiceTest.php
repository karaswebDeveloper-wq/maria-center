<?php

declare(strict_types=1);

namespace Tests\Unit\Reports;

use App\Models\AcademicPeriod;
use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\Stage;
use App\Models\Student;
use App\Services\Reports\MonthlyStatisticsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MonthlyStatisticsServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_groups_amounts_by_stage_and_grade(): void
    {
        $period = AcademicPeriod::factory()->create();
        $stage = Stage::factory()->create(['name' => 'الابتدائية']);
        $grade = Grade::factory()->create(['stage_id' => $stage->id, 'name' => 'الأول']);
        $student = Student::factory()->create(['grade_id' => $grade->id]);

        Enrollment::factory()->create([
            'period_id' => $period->id,
            'student_id' => $student->id,
            'fee_amount' => 140,
            'support_amount' => 40,
            'status' => 'active',
        ]);

        $groups = (new MonthlyStatisticsService())->get($period);
        $row = $groups['الابتدائية']->first();

        $this->assertSame(1, $row->students_count);
        $this->assertSame(100.0, (float) $row->parent_amount);
        $this->assertSame(40.0, (float) $row->support_amount);
        $this->assertSame(140.0, (float) $row->total_amount);
    }
}