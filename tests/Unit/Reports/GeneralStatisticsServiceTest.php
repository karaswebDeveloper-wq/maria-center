<?php

declare(strict_types=1);

namespace Tests\Unit\Reports;

use App\Models\Grade;
use App\Models\Stage;
use App\Models\Student;
use App\Services\Reports\GeneralStatisticsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GeneralStatisticsServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_counts_only_active_students(): void
    {
        $stage = Stage::factory()->create();
        $grade = Grade::factory()->create(['stage_id' => $stage->id]);

        Student::factory()->create(['grade_id' => $grade->id, 'is_active' => true]);
        Student::factory()->create(['grade_id' => $grade->id, 'is_active' => false]);

        $service = new GeneralStatisticsService();

        $this->assertSame(1, $service->totalStudents());
        $this->assertSame(1, $service->get()->first()->grades->first()->students_count);
    }
}