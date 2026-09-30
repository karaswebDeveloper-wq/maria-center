<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\DurationType;
use App\Models\AcademicPeriod;
use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\Stage;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Services\EnrollmentService;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use RuntimeException;

#[Layout('components.layouts.app')]
class EnrollmentForm extends Component
{
    public ?Enrollment $enrollment = null;

    public ?int $stageId = null;

    public ?int $gradeId = null;

    public ?int $studentId = null;

    public ?int $teacherId = null;

    public ?int $subjectId = null;

    public ?int $periodId = null;

    public string $durationType = '';

    public ?float $feeAmount = null;

    public ?float $supportAmount = 0;

    public ?string $notes = null;

    public function mount(?Enrollment $enrollment = null): void
    {
        if ($enrollment?->exists) {
            $enrollment->loadMissing('student.grade');

            $this->enrollment = $enrollment;
            $this->stageId = $enrollment->student->grade->stage_id;
            $this->gradeId = $enrollment->student->grade_id;
            $this->studentId = $enrollment->student_id;
            $this->teacherId = $enrollment->teacher_id;
            $this->subjectId = $enrollment->subject_id;
            $this->periodId = $enrollment->period_id;
            $this->durationType = $enrollment->duration_type->value;
            $this->feeAmount = (float) $enrollment->fee_amount;
            $this->supportAmount = (float) $enrollment->support_amount;
            $this->notes = $enrollment->notes;
        }
    }

    public function updatedStageId(): void
    {
        $this->gradeId = null;
        $this->studentId = null;
    }

    public function updatedGradeId(): void
    {
        $this->studentId = null;
    }

    protected function rules(): array
    {
        return [
            'studentId' => ['required', 'integer', 'exists:students,id'],
            'teacherId' => ['required', 'integer', 'exists:teachers,id'],
            'subjectId' => ['required', 'integer', 'exists:subjects,id'],
            'periodId' => ['required', 'integer', 'exists:academic_periods,id'],
            'durationType' => ['required', Rule::in(array_column(DurationType::cases(), 'value'))],
            'feeAmount' => ['required', 'numeric', 'min:0'],
            'supportAmount' => ['required', 'numeric', 'min:0', 'lte:feeAmount'],
            'notes' => ['nullable', 'string'],
        ];
    }

    protected function messages(): array
    {
        return [
            'studentId.required' => 'الطالب مطلوب.',
            'teacherId.required' => 'المدرس مطلوب.',
            'subjectId.required' => 'المادة مطلوبة.',
            'periodId.required' => 'الفترة الدراسية مطلوبة.',
            'durationType.required' => 'مدة الاشتراك مطلوبة.',
            'feeAmount.required' => 'قيمة الاشتراك مطلوبة.',
            'supportAmount.lte' => 'قيمة الدعم لا يمكن أن تتجاوز قيمة الاشتراك.',
        ];
    }

    public function save(EnrollmentService $service): void
    {
        $this->validate();

        $data = [
            'student_id' => $this->studentId,
            'teacher_id' => $this->teacherId,
            'subject_id' => $this->subjectId,
            'period_id' => $this->periodId,
            'duration_type' => $this->durationType,
            'fee_amount' => $this->feeAmount,
            'support_amount' => $this->supportAmount,
            'notes' => $this->notes,
        ];

        try {
            if ($this->enrollment) {
                $service->update($this->enrollment, $data);
            } else {
                $service->create($data);
            }
        } catch (RuntimeException $e) {
            $this->addError('subjectId', $e->getMessage());

            return;
        }

        session()->flash('success', $this->enrollment ? 'تم تحديث الاشتراك بنجاح.' : 'تم إنشاء الاشتراك بنجاح.');

        $this->redirect(route('home'), navigate: false); // ⚠️ سيتغيّر لـ enrollments.index في 7b
    }

    public function render()
    {
        return view('livewire.enrollment-form', [
            'stages' => Stage::query()->active()->orderBy('name')->get(),
            'grades' => $this->stageId
                ? Grade::query()->where('stage_id', $this->stageId)->active()->orderBy('sort_order')->get()
                : collect(),
            'students' => $this->gradeId
                ? Student::query()->where('grade_id', $this->gradeId)->active()->orderBy('name')->get()
                : collect(),
            'teachers' => Teacher::query()->active()->orderBy('name')->get(),
            'subjects' => Subject::query()->active()->orderBy('name')->get(),
            'periods' => AcademicPeriod::query()->open()->orderByDesc('year')->orderByDesc('month')->get(),
            'durationTypes' => DurationType::cases(),
        ]);
    }
}