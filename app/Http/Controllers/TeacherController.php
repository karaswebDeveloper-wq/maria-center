<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;
use App\Models\Teacher;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TeacherController extends Controller
{
    public function index(): View
    {
        $teachers = Teacher::query()->orderBy('name')->paginate(20);

        return view('teachers.index', compact('teachers'));
    }

    public function create(): View
    {
        return view('teachers.create');
    }

    public function store(StoreTeacherRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('teachers', 'public');
        }

        Teacher::query()->create($data);

        return redirect()->route('teachers.index')->with('success', 'تم إضافة المدرس بنجاح.');
    }

    public function edit(Teacher $teacher): View
    {
        return view('teachers.edit', compact('teacher'));
    }

    public function update(UpdateTeacherRequest $request, Teacher $teacher): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($teacher->image_path) {
                Storage::disk('public')->delete($teacher->image_path);
            }

            $data['image_path'] = $request->file('image')->store('teachers', 'public');
        }

        $teacher->update($data);

        return redirect()->route('teachers.index')->with('success', 'تم تحديث بيانات المدرس بنجاح.');
    }

    public function destroy(Teacher $teacher): RedirectResponse
    {
        try {
            $teacher->delete(); // soft delete; image file is intentionally kept in case the teacher is restored
        } catch (QueryException) {
            return back()->with('error', 'لا يمكن حذف هذا المدرس لوجود اشتراكات مرتبطة به.');
        }

        return redirect()->route('teachers.index')->with('success', 'تم حذف المدرس بنجاح.');
    }
}