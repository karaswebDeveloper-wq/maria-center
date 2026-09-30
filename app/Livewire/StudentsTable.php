<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Grade;
use App\Models\Stage;
use App\Models\Student;
use Illuminate\Database\QueryException;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class StudentsTable extends Component
{
    use WithPagination;

    #[Url(as: 'q', history: true)]
    public string $search = '';

    #[Url(as: 'stage', history: true)]
    public ?int $stageId = null;

    #[Url(as: 'grade', history: true)]
    public ?int $gradeId = null;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStageId(): void
    {
        $this->gradeId = null;
        $this->resetPage();
    }

    public function updatedGradeId(): void
    {
        $this->resetPage();
    }

    public function delete(Student $student): void
    {
        try {
            $student->delete();
            session()->flash('success', 'تم حذف الطالب بنجاح.');
        } catch (QueryException) {
            session()->flash('error', 'لا يمكن حذف هذا الطالب لوجود اشتراكات مرتبطة به.');
        }
    }

    public function render()
    {
        $students = Student::query()
            ->with(['family', 'grade.stage'])
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('name', 'like', "%{$this->search}%")
                        ->orWhere('student_code', 'like', "%{$this->search}%")
                        ->orWhere('phone', 'like', "%{$this->search}%");
                });
            })
            ->when($this->gradeId, fn ($query) => $query->where('grade_id', $this->gradeId))
            ->when($this->stageId && ! $this->gradeId, function ($query) {
                $query->whereHas('grade', fn ($query) => $query->where('stage_id', $this->stageId));
            })
            ->orderBy('name')
            ->paginate(15);

        return view('livewire.students-table', [
            'students' => $students,
            'stages' => Stage::query()->active()->orderBy('name')->get(),
            'grades' => $this->stageId
                ? Grade::query()->where('stage_id', $this->stageId)->active()->orderBy('sort_order')->get()
                : collect(),
        ]);
    }
}