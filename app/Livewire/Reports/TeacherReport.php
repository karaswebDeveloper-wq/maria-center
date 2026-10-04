<?php // TeacherReport.php

declare(strict_types=1);

namespace App\Livewire\Reports;

use App\Models\AcademicPeriod;
use App\Models\Grade;
use App\Models\Stage;
use App\Models\Subject;
use App\Models\Teacher;
use App\Services\Reports\TeacherReportService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class TeacherReport extends Component
{
    public ?int $periodId = null;

    public ?int $teacherId = null;

    public ?int $subjectId = null;

    public ?int $stageId = null;

    public ?int $gradeId = null;

    public function updatedStageId(): void
    {
        $this->gradeId = null;
    }

    public function render()
    {
        $rows = app(TeacherReportService::class)->get([
            'period_id' => $this->periodId,
            'teacher_id' => $this->teacherId,
            'subject_id' => $this->subjectId,
            'stage_id' => $this->stageId,
            'grade_id' => $this->gradeId,
        ]);

        return view('reports.teacher-report', [
            'rows' => $rows,
            'periods' => AcademicPeriod::query()->orderByDesc('year')->orderByDesc('month')->get(),
            'teachers' => Teacher::query()->active()->orderBy('name')->get(),
            'subjects' => Subject::query()->active()->orderBy('name')->get(),
            'stages' => Stage::query()->active()->orderBy('name')->get(),
            'grades' => $this->stageId
                ? Grade::query()->where('stage_id', $this->stageId)->active()->orderBy('sort_order')->get()
                : collect(),
        ]);
    }
}
