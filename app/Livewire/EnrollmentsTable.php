<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\AcademicPeriod;
use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\Stage;
use App\Models\Subject;
use App\Models\Teacher;
use App\Services\EnrollmentService;
use Illuminate\Database\QueryException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class EnrollmentsTable extends Component
{
    use WithPagination;

    #[Url(as: 'period', history: true)]
    public ?int $periodId = null;

    #[Url(as: 'teacher', history: true)]
    public ?int $teacherId = null;

    #[Url(as: 'subject', history: true)]
    public ?int $subjectId = null;

    #[Url(as: 'stage', history: true)]
    public ?int $stageId = null;

    #[Url(as: 'grade', history: true)]
    public ?int $gradeId = null;

    #[Url(as: 'status', history: true)]
    public string $status = 'active';

    public function updatedStageId(): void
    {
        $this->gradeId = null;
        $this->resetPage();
    }

    public function updatedPeriodId(): void
    {
        $this->resetPage();
    }

    public function updatedTeacherId(): void
    {
        $this->resetPage();
    }

    public function updatedSubjectId(): void
    {
        $this->resetPage();
    }

    public function updatedGradeId(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function cancel(Enrollment $enrollment, EnrollmentService $service): void
    {
        $service->cancel($enrollment);

        session()->flash('success', 'تم إلغاء الاشتراك بنجاح.');
    }

    public function reactivate(Enrollment $enrollment, EnrollmentService $service): void
    {
        try {
            $service->reactivate($enrollment);
            session()->flash('success', 'تم إعادة تفعيل الاشتراك بنجاح.');
        } catch (QueryException) {
            session()->flash('error', 'تعذّرت إعادة التفعيل.');
        }
    }

    public function render()
    {
        $enrollments = Enrollment::query()
            ->with(['student.grade.stage', 'teacher', 'subject', 'academicPeriod'])
            ->withSum('payments', 'amount')
            ->when($this->status !== 'all', fn ($query) => $query->where('status', $this->status))
            ->when($this->periodId, fn ($query) => $query->where('period_id', $this->periodId))
            ->when($this->teacherId, fn ($query) => $query->where('teacher_id', $this->teacherId))
            ->when($this->subjectId, fn ($query) => $query->where('subject_id', $this->subjectId))
            ->when($this->gradeId, function ($query) {
                $query->whereHas('student', fn ($query) => $query->where('grade_id', $this->gradeId));
            })
            ->when($this->stageId && ! $this->gradeId, function ($query) {
                $query->whereHas('student.grade', fn ($query) => $query->where('stage_id', $this->stageId));
            })
            ->orderByDesc('id')
            ->paginate(15);

        return view('livewire.enrollments-table', [
            'enrollments' => $enrollments,
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