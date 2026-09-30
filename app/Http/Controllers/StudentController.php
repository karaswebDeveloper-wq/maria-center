<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Family;
use App\Models\Grade;
use App\Models\Stage;
use App\Models\Student;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(): View
    {
        // Listing, search and stage/grade filtering are handled entirely
        // by the <livewire:students-table /> component in this view.
        return view('students.index');
    }

    public function create(): View
    {
        return view('students.create', $this->formData());
    }

    public function store(StoreStudentRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('students', 'public');
        }

        Student::query()->create($data);

        return redirect()->route('students.index')->with('success', 'تم إضافة الطالب بنجاح.');
    }

    public function edit(Student $student): View
    {
        return view('students.edit', ['student' => $student] + $this->formData());
    }

    public function update(UpdateStudentRequest $request, Student $student): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($student->image_path) {
                Storage::disk('public')->delete($student->image_path);
            }

            $data['image_path'] = $request->file('image')->store('students', 'public');
        }

        $student->update($data);

        return redirect()->route('students.index')->with('success', 'تم تحديث بيانات الطالب بنجاح.');
    }

    public function destroy(Student $student): RedirectResponse
    {
        try {
            $student->delete(); // soft delete
        } catch (QueryException) {
            return back()->with('error', 'لا يمكن حذف هذا الطالب لوجود اشتراكات مرتبطة به.');
        }

        return redirect()->route('students.index')->with('success', 'تم حذف الطالب بنجاح.');
    }

    /**
     * Shared reference data for the create/edit forms.
     *
     * @return array{families: \Illuminate\Support\Collection, stages: \Illuminate\Support\Collection, grades: \Illuminate\Support\Collection}
     */
    private function formData(): array
    {
        return [
            'families' => Family::query()->active()->orderBy('name')->get(),
            'stages' => Stage::query()->active()->orderBy('name')->get(),
            'grades' => Grade::query()->active()->orderBy('sort_order')->get(['id', 'name', 'stage_id']),
        ];
    }
}