<?php

declare(strict_types=1);

namespace Tests\Unit\Reports;

use App\Models\Enrollment;
use App\Models\Subject;
use App\Models\Teacher;
use App\Services\Reports\TeacherReportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeacherReportServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_filters_by_teacher(): void
    {
        $teacherA = Teacher::factory()->create();
        $teacherB = Teacher::factory()->create();

        Enrollment::factory()->create(['teacher_id' => $teacherA->id]);
        Enrollment::factory()->create(['teacher_id' => $teacherB->id]);

        $rows = (new TeacherReportService())->get(['teacher_id' => $teacherA->id]);

        $this->assertCount(1, $rows);
        $this->assertSame($teacherA->id, $rows->first()->teacher_id);
    }

    public function test_cancelled_enrollments_are_excluded(): void
    {
        Enrollment::factory()->create(['status' => 'cancelled']);

        $rows = (new TeacherReportService())->get([]);

        $this->assertCount(0, $rows);
    }

    public function test_filters_by_subject(): void
    {
        $subjectA = Subject::factory()->create();
        $subjectB = Subject::factory()->create();

        Enrollment::factory()->create(['subject_id' => $subjectA->id]);
        Enrollment::factory()->create(['subject_id' => $subjectB->id]);

        $rows = (new TeacherReportService())->get(['subject_id' => $subjectA->id]);

        $this->assertCount(1, $rows);
    }
}